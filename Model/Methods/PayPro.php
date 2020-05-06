<?php

namespace Magento\PayProPaymentGateway\Model\Methods;

use Magento\Framework\DataObject;
use Magento\Payment\Model\Method\AbstractMethod;


class PayPro extends AbstractMethod
{
	protected $payMethodCode = null;

	/**
	 * @param string $currencyCode
	 *
	 * @return bool
	 */
	public function canUseForCurrency($currencyCode)
	{
		return $currencyCode === 'EUR';
	}

	/**
	 * @param DataObject $data
	 *
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function assignData(DataObject $data)
	{
		parent::assignData($data);

		if (isset($this->payMethodCode)) {
			$this->getInfoInstance()->setAdditionalInformation('pay_method', $this->payMethodCode);
		}

		return $this;
	}
}
