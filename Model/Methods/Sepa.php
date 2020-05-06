<?php

namespace Magento\PayProPaymentGateway\Model\Methods;


class Sepa extends PayPro
{
	protected $_code = 'paypro_sepa';
	protected $payMethodCode = 'banktransfer/sepa';
}
