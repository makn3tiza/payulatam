<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Controller\Payment;

use Magento\Framework\Exception\LocalizedException;

class Notify extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Action\Context
     */
    protected $context;

    /**
     * @var \makn3tiza\Payulatam\Model\ClientFactory
     */
    protected $clientFactory;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \makn3tiza\Payulatam\Logger\Logger
     */
    protected $logger;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \makn3tiza\Payulatam\Model\ClientFactory $clientFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param \makn3tiza\Payulatam\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \makn3tiza\Payulatam\Model\ClientFactory $clientFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \makn3tiza\Payulatam\Logger\Logger $logger
    ) {
        parent::__construct($context);
        $this->context = $context;
        $this->clientFactory = $clientFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        /**
         * @var $client \makn3tiza\Payulatam\Model\Client
         */
        $request = $this->context->getRequest();
        try {
            $client = $this->clientFactory->create();
            $response = $client->orderConsumeNotification($request);
            $clientOrderHelper = $client->getOrderHelper();
            if ($clientOrderHelper->canProcessNotification($response['referenceCode'])) {
                return $clientOrderHelper->processNotification(
                    $response['referenceCode'],
                    $response['status'],
                    $response['amount']
                );
            }
        } catch (LocalizedException $e) {
            $this->logger->critical($e);
        }
        /**
         * @var $resultForward \Magento\Framework\Controller\Result\Forward
         */
        $resultForward = $this->resultForwardFactory->create();
        $resultForward->forward('noroute');
        return $resultForward;
    }
}
