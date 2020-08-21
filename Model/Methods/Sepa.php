<?php

namespace PayPro\PaymentGateway\Model\Methods;

class Sepa extends PayPro
{
	protected $_code = 'paypro_sepa';
	protected $payMethodCode = 'banktransfer/sepa';
}
