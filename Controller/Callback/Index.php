<?php


namespace Magento\PayProPaymentGateway\Controller\Callback;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\PayProPaymentGateway\Gateway;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Framework\DB\Transaction;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Framework\Controller\Result\JsonFactory;


class Index extends Action {

	/** @var Gateway */
	private $gateway;

	/** @var OrderRepositoryInterface */
	private $orderRepository;

	/** @var JsonFactory */
	private $jsonFactory;

	/** @var InvoiceService */
	private $invoiceService;

	/** @var InvoiceSender */
	private $invoiceSender;

	/** @var Transaction */
	private $transaction;

	/**
	 * @param Context $context
	 * @param Gateway $gateway
	 * @param OrderRepositoryInterface $orderRepository
	 * @param JsonFactory $jsonFactory
	 */
	public function __construct(
		Context $context,
		Gateway $gateway,
		OrderRepositoryInterface $orderRepository,
		InvoiceService $invoiceService,
		InvoiceSender $invoiceSender,
		Transaction $transaction,
		JsonFactory $jsonFactory
	) {
		parent::__construct($context);
		$this->gateway = $gateway;
		$this->orderRepository = $orderRepository;
		$this->invoiceService = $invoiceService;
		$this->invoiceSender = $invoiceSender;
		$this->transaction = $transaction;
		$this->jsonFactory = $jsonFactory;
	}

	/**
	 * Handle callback
	 *
	 * @return \Magento\Framework\Controller\ResultInterface
	 */
	public function execute() {
		$paymentHash = $this->getRequest()->getParam('payment_hash');
		$result = $this->jsonFactory->create();

		if ($paymentHash) {
			// Get the payment
			$payment = $this->gateway->getPayment($paymentHash);

			if (isset($payment['custom']) && !empty($payment['custom'])) {
				$order = $this->orderRepository->get($payment['custom']);

				if ($order) {
					// Change order state
					if ($payment['current_status'] === 'completed') {
						if($order->canInvoice()) {
							$invoice = $this->invoiceService->prepareInvoice($order);
							$invoice->register();
							$invoice->pay();
							$invoice->save();

							$transactionSave = $this->transaction
								->addObject($invoice)
								->addObject($invoice->getOrder());
							$transactionSave->save();

							if (!$invoice->getEmailSent()) {
								$this->invoiceSender->send($invoice);
								$order->addStatusHistoryComment('Payment Completed.', $invoice->getId())
								      ->setIsCustomerNotified(true)
								      ->save();
							}
						}

						$order->setState(Order::STATE_PROCESSING)
							->setStatus(Order::STATE_PROCESSING)
							->save();
					} else if ($payment['current_status'] === 'canceled') {
						$order->setState(Order::STATE_CANCELED)
							->setStatus(Order::STATE_CANCELED)
							->save();
					}

					return $result->setData(['success' => true]);
				}
			}
		}

		return $result->setData(['success' => false]);
	}
}