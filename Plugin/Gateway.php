<?php

namespace PayPro\PaymentGateway\Plugin;

use Magento\Framework\UrlInterface;
use Magento\Sales\Model\Order;
use Magento\Framework\App\Config\ScopeConfigInterface;

use PayPro\Client;

class Gateway {

	/** @var Client */
	private $client;

	private $testMode;

	private $productID;

	/** @var UrlInterface */
	private $urlBuilder;

	public function __construct(
		ScopeConfigInterface $scopeConfig,
		UrlInterface $urlBuilder
	) {
		$this->client = new Client($scopeConfig->getValue('payment/paypro_gateway/api_key'));
		$this->testMode = $scopeConfig->getValue('payment/paypro_gateway/testmode') === '1';
		$this->productID = $scopeConfig->getValue('payment/paypro_gateway/product_id');

		$this->urlBuilder = $urlBuilder;
	}

	public function testModeEnabled() {
		return $this->testMode;
	}

	/**
	 * Create a new payment
	 *
	 * @param $data
	 *
	 * @return Array
	 */
	public function createPayment($data) {
		$params = array_merge($data, [
			'test_mode' => $this->testMode,
		]);

		if ($this->productID) {
			$this->client->setCommand('create_product_payment');
			$this->client->setParams(array_merge($params, ['product_id' => $this->productID]));
		} else {
			$this->client->setCommand('create_payment');
			$this->client->setParams($params);
		}

		try {
			$response = $this->client->execute();
			if ($response['return'] === 'API key not valid') $response = array('errors' => 'true', 'return' => self::getUserFriendlyError($repsonse['return']));
			return $response;
		} catch (\Exception $exception) {
			return array('errors' => 'true', 'return' => self::getUserFriendlyError(''));
		}
	}

	/**
	 * Get a payment by hash
	 *
	 * @param $paymentHash
	 *
	 * @return null
	 */
	public function getPayment($paymentHash) {
		$this->client->setCommand('get_sale');
		$this->client->setParams([
			'payment_hash' => $paymentHash,
		]);

		try {
			$response = $this->client->execute();

			return $response['return'];
		} catch (\Exception $exception) {
			return null;
		}
	}

	public function getIdealIssuers() {
		$this->client->setCommand('get_all_pay_methods');

		try {
			$response = $this->client->execute();
			$methods = $response['return']['data']['ideal']['methods'];

			$issuers = [];
			foreach ($methods as $method) {
				$issuers[] = ['id' => $method['id'], 'label' => $method['name']];
			}

			return $issuers;
		} catch (\Exception $exception) {
			return null;
		}
	}

	/**
	 * change api response error to userfriendly error message
	 *
	 * @param responseError from api
	 *
	 * @return String
	 */
	public function getUserFriendlyError($responseError) {
		switch ($responseError) {
			case 'Not subscribed to money transfer service':
				$newMessage = _('Can\'t use this payment method, please try a different method.');
				break;
			
			default:
				$newMessage = _('Something went wrong during checkout, please try again.');
				break;
		}
		return $newMessage;
	}
}
