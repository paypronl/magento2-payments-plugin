<?php

namespace PayPro\PaymentGateway\Model\Ui;

use Magento\Framework\App\CacheInterface;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

use PayPro\PaymentGateway\Plugin\Gateway;

/**
 * Class ConfigProvider
 */
class ConfigProvider implements ConfigProviderInterface
{
	private $cache;
	private $gateway;

	public function __construct(
		CacheInterface $cache,
		Gateway $gateway
	) {
		$this->cache = $cache;
		$this->gateway = $gateway;
	}

	private function getIssuers() {
		$cacheKey = 'paypro_issuers_' . ($this->gateway->testModeEnabled() ? 'test' : 'live');
		$cachedIssuers = $this->cache->load($cacheKey);

		if ($cachedIssuers) {
			return json_decode($cachedIssuers, true);
		}

		$issuers = $this->gateway->getIdealIssuers() ?? [];

		// Cache the issuers for a day if array not empty
		if (!empty($issuers)) {
			$this->cache->save(json_encode($issuers), $cacheKey, [], 60 * 60 * 24);
		}

		return $issuers;
	}

	/**
	 * Retrieve assoc array of checkout configuration
	 *
	 * @return array
	 */
	public function getConfig()
	{
		return [
			'payment' => [
				'issuers' => array_merge([['id' => '', 'label' => __('Select your bank')]], $this->getIssuers()),
			],
		];
	}
}
