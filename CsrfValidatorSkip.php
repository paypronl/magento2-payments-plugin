<?php


namespace Magento\PayProPaymentGateway;

/**
 * Class CsrfValidatorSkip
 * 
 * https://gist.github.com/ananth-iyer/59ecfabcbca73d6c2e3eeb986ed2f3c4
 * 
 * @package Magento\PayProPaymentGateway
 */
class CsrfValidatorSkip
{
	/**
	 * @param \Magento\Framework\App\Request\CsrfValidator $subject
	 * @param \Closure $proceed
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @param \Magento\Framework\App\ActionInterface $action
	 */
	public function aroundValidate(
		$subject,
		\Closure $proceed,
		$request,
		$action
	) {
		if ($request->getModuleName() == 'paypro') {
			return; // Skip CSRF check
		}
		
		$proceed($request, $action); // Proceed Magento 2 core functionalities
	}
}