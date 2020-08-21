<?php

namespace PayPro\PaymentGateway\Model\Methods;

use Magento\Framework\DataObject;

class Ideal extends PayPro
{
	protected $_code = 'paypro_ideal';

	/**
	 * @param DataObject $data
	 *
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function assignData(DataObject $data)
	{
		parent::assignData($data);

		$additionalData = $data->getAdditionalData();
		$this->getInfoInstance()->setAdditionalInformation('pay_method', $additionalData['issuer']);

		return $this;
	}
}
