<?php

namespace Magento\PayProPaymentGateway\Model\Methods;


class SepaOnce extends PayPro
{
	protected $_code = 'paypro_sepa_once';
	protected $payMethodCode = 'directdebit/sepa-once';
}
