<?php

namespace PayPro\PaymentGateway\Controller\StartPayment;

use Magento\Checkout\Model\Session;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\UrlInterface;
use Magento\Sales\Model\Order;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;

use PayPro\PaymentGateway\Plugin\Gateway;

class Index extends Action {

	/** @var Session */
	private $checkoutSession;

	/** @var UrlInterface */
	private $urlBuilder;

	/** @var Resolver */
	private $localeResolver;

	/** @var Gateway */
	private $gateway;

	/**
	* @param Context $context
	* @param Session $checkoutSession
	* @param UrlInterface $urlBuilder
	* @param Resolver $localeResolver
	* @param Gateway $gateway
	*/
	public function __construct(
		Context $context,
		Session $checkoutSession,
		UrlInterface $urlBuilder,
		Resolver $localeResolver,
		Gateway $gateway
	) {
		parent::__construct($context);
		$this->checkoutSession = $checkoutSession;
		$this->urlBuilder = $urlBuilder;
		$this->localeResolver = $localeResolver;
		$this->gateway = $gateway;
	}

	/**
	* Initialize redirect to PayPro
	*/
	public function execute() {
		$order = $this->checkoutSession->getLastRealOrder();

		if (!$order) {
			return $this->_redirect('checkout/cart');
		}

		$payment = $order->getPayment();
		$data = array_filter($payment->getAdditionalInformation(), function($key) {
			return $key !== 'method_title';
		}, ARRAY_FILTER_USE_KEY);

		$redirectUrl = $this->urlBuilder->getRouteUrl('paypro/redirect', ['order_id' => $order->getId()]);
		$billingAddress = $order->getBillingAddress();

		$response = $this->gateway->createPayment(array_merge([
			'amount' => (int) (round($order->getGrandTotal() * 100, 0)),
			'return_url' => $redirectUrl,
			'cancel_url' => $redirectUrl,
			'postback_url' => $this->urlBuilder->getRouteUrl('paypro/callback'),
			'description' => $order->getRealOrderId() . ' - ' . $order->getStore()->getFrontendName(),
			'locale' => $this->getLocale(),
			'custom' => $order->getId(),
			'consumer_email' => $billingAddress->getEmail(),
			'consumer_firstname' => $billingAddress->getFirstname(),
			'consumer_name' => trim($billingAddress->getMiddlename() . ' ' . $billingAddress->getLastname()),
			'consumer_phone' => $billingAddress->getTelephone(),
			'consumer_address' => join(' ', $billingAddress->getStreet()),
			'consumer_city' => $billingAddress->getCity(),
			'consumer_companyname' => $billingAddress->getCompany(),
			'consumer_country' => $billingAddress->getCountryId(),
			'consumer_postal' => $billingAddress->getPostcode(),
		], $data));

		if ($response['errors'] === 'true') {
			$this->checkoutSession->restoreQuote();
			$this->messageManager->addNoticeMessage($this->gateway->getUserFriendlyError($response['return']));
			return $this->resultRedirectFactory->create()->setUrl('/checkout/cart');
		}

		$order
			->setPayproPaymentHash($response['return']['payment_hash'])
			->setState(Order::STATE_PENDING_PAYMENT)
			->setStatus(Order::STATE_PENDING_PAYMENT)
			->save();

		return $this->resultRedirectFactory->create()->setUrl($response['return']['payment_url']);
	}

	private function getLocale() {
		$magentoLocale = $this->localeResolver->getLocale();

		if (substr($magentoLocale, 0, 2) === 'nl') {
			return 'NL';
		}

		return 'EN';
	}
}
