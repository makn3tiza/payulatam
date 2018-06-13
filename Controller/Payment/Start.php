<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Controller\Payment;

use Magento\Framework\Exception\LocalizedException;

class Start extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \makn3tiza\Payulatam\Model\ClientFactory
     */
    protected $clientFactory;

    /**
     * @var \makn3tiza\Payulatam\Model\Order
     */
    protected $orderHelper;

    /**
     * @var \makn3tiza\Payulatam\Model\Session
     */
    protected $session;

    /**
     * @var \makn3tiza\Payulatam\Logger\Logger
     */
    protected $logger;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \makn3tiza\Payulatam\Model\ClientFactory $clientFactory
     * @param \makn3tiza\Payulatam\Model\Order $orderHelper
     * @param \makn3tiza\Payulatam\Model\Session $session
     * @param \makn3tiza\Payulatam\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \makn3tiza\Payulatam\Model\ClientFactory $clientFactory,
        \makn3tiza\Payulatam\Model\Order $orderHelper,
        \makn3tiza\Payulatam\Model\Session $session,
        \makn3tiza\Payulatam\Logger\Logger $logger
    ) {
        parent::__construct($context);
        $this->clientFactory = $clientFactory;
        $this->orderHelper = $orderHelper;
        $this->session = $session;
        $this->logger = $logger;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /**
         * @var $clientOrderHelper \makn3tiza\Payulatam\Model\Client\OrderInterface
         * @var $resultRedirect \Magento\Framework\Controller\Result\Redirect
         */
        $resultRedirect = $this->resultRedirectFactory->create();
        $redirectUrl = 'checkout/cart';
        $redirectParams = [];
        $orderId = $this->orderHelper->getOrderIdForPaymentStart();
        if ($orderId) {
            $order = $this->orderHelper->loadOrderById($orderId);
            if ($this->orderHelper->canStartFirstPayment($order)) {
                try {
                    $client = $this->clientFactory->create();

                    $clientOrderHelper = $client->getOrderHelper();
                    $orderData = $clientOrderHelper->getDataForOrderCreate($order);

                    $result = $client->orderCreate($orderData);

                    $this->orderHelper->addNewOrderTransaction(
                        $order,
                        $result['orderId'],
                        $result['extOrderId'],
                        $clientOrderHelper->getNewStatus()
                    );
                    $this->orderHelper->setNewOrderStatus($order);

                    $configHelper = $client->getConfigHelper();

                    $this->session->setGatewayUrl($configHelper->getConfig('url'));

                    $redirectUrl = $result['redirectUri'];
                } catch (LocalizedException $e) {
                    $this->logger->critical($e);
                    $redirectUrl = 'payulatam/payment/end';
                    $redirectParams = ['exception' => '1'];
                }
                $this->session->setLastOrderId($orderId);
            }
        }
        $resultRedirect->setPath($redirectUrl, $redirectParams);
        return $resultRedirect;
    }
}
