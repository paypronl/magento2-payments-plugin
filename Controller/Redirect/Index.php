<?php

namespace PayPro\PaymentGateway\Controller\Redirect;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Checkout\Model\Session;
use Magento\Sales\Model\Order;
use PayPro\PaymentGateway\Plugin\Gateway;

class Index extends Action {

	/** @var Session */
	private $checkoutSession;

	/** @var PaymentHelper */
	private $paymentHelper;

	/** @var OrderRepositoryInterface */
	private $orderRepository;

	/** @var Gateway */
	private $gateway;

	/**
	 * @param Context $context
	 * @param Session $checkoutSession
	 * @param PaymentHelper $paymentHelper
	 * @param OrderRepositoryInterface $orderRepository
	 * @param Gateway $gateway
	 */
	public function __construct(
		Context $context,
		Session $checkoutSession,
		PaymentHelper $paymentHelper,
		OrderRepositoryInterface $orderRepository,
		Gateway $gateway
	) {
		parent::__construct($context);
		$this->checkoutSession = $checkoutSession;
		$this->paymentHelper = $paymentHelper;
		$this->orderRepository = $orderRepository;
		$this->gateway = $gateway;
	}

	/**
	 * Handle redirect
	 */
	public function execute() {
		$params = $this->getRequest()->getParams();

		if (!isset($params['order_id'])) {
			$this->messageManager->addNoticeMessage(__('Invalid return from PayPro.'));
			return $this->_redirect('checkout/cart');
		}

		// Get the order
		$order = $this->orderRepository->get($params['order_id']);

		if (!$order) {
			$this->messageManager->addNoticeMessage(__('Order not found'));
			return $this->_redirect('checkout/cart');
		}

		// Because we can't rely on the PayPro callback is called before the redirect url,
		// we need to fetch the transaction from PayPro to check the status.
		// We don't change the order state here, this is always done in the Callback.
		$payment = $this->gateway->getPayment($order->getPayproPaymentHash());
		if ($payment['current_status'] === 'completed' || $payment['current_status'] === 'open') {
			try {
				if ($payment['current_status'] === 'open') {
					$msg = 'We have not received a definite payment status. Depending on the payment method, it may take a while until we receive the payment';
					$this->messageManager->addNoticeMessage(__($msg));
				}

				// Redirect to success
				$this->checkoutSession->start();
				$this->_redirect('checkout/onepage/success?utm_nooverride=1');
			} catch (\Exception $e) {
				$this->messageManager->addNoticeMessage(__('Something went wrong.'));
				$this->_redirect('checkout/cart');
			}
		} else {
			// canceled
			$this->checkoutSession->restoreQuote();
			if ($payment['current_status'] === 'canceled') {
				$this->messageManager->addNoticeMessage(__('Payment canceled, please try again.'));
			} else {
				$this->messageManager->addNoticeMessage(__('Something went wrong.'));
			}

			$this->_redirect('checkout/cart');
		}
	}
}