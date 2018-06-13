<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Controller\Payment;

use Magento\Framework\Exception\LocalizedException;

class End extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Session\SuccessValidator
     */
    protected $successValidator;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \makn3tiza\Payulatam\Model\Session
     */
    protected $session;

    /**
     * @var \makn3tiza\Payulatam\Model\ClientFactory
     */
    protected $clientFactory;

    /**
     * @var \Magento\Framework\App\Action\Context
     */
    protected $context;

    /**
     * @var \makn3tiza\Payulatam\Model\Order
     */
    protected $orderHelper;

    /**
     * @var \makn3tiza\Payulatam\Logger\Logger
     */
    protected $logger;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\Session\SuccessValidator $successValidator
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \makn3tiza\Payulatam\Model\Session $session
     * @param \makn3tiza\Payulatam\Model\ClientFactory $clientFactory
     * @param \makn3tiza\Payulatam\Model\Order $orderHelper
     * @param \makn3tiza\Payulatam\Logger\Logger $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session\SuccessValidator $successValidator,
        \Magento\Checkout\Model\Session $checkoutSession,
        \makn3tiza\Payulatam\Model\Session $session,
        \makn3tiza\Payulatam\Model\ClientFactory $clientFactory,
        \makn3tiza\Payulatam\Model\Order $orderHelper,
        \makn3tiza\Payulatam\Logger\Logger $logger
    ) {
        parent::__construct($context);
        $this->context = $context;
        $this->successValidator = $successValidator;
        $this->checkoutSession = $checkoutSession;
        $this->session = $session;
        $this->clientFactory = $clientFactory;
        $this->orderHelper = $orderHelper;
        $this->logger = $logger;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /**
         * @var $clientOrderHelper \makn3tiza\Payulatam\Model\Client\OrderInterface
         */
        $resultRedirect = $this->resultRedirectFactory->create();
        $redirectUrl = '/';
        try {
            if ($this->successValidator->isValid()) {
                $redirectUrl = 'payulatam/payment/error';
                $this->session->setLastOrderId(null);
                $clientOrderHelper = $this->getClientOrderHelper();
                if ($this->orderHelper->paymentSuccessCheck() && $clientOrderHelper->paymentSuccessCheck()) {
                    $redirectUrl = 'checkout/onepage/success';
                }

            } else {
                if ($this->session->getLastOrderId()) {
                    $redirectUrl = 'payulatam/payment/repeat_error';
                    $clientOrderHelper = $this->getClientOrderHelper();
                    if ($this->orderHelper->paymentSuccessCheck() && $clientOrderHelper->paymentSuccessCheck()) {
                        $redirectUrl = 'payulatam/payment/repeat_success';
                    }
                }
            }
        } catch (LocalizedException $e) {
            $this->logger->critical($e);
        }
        $resultRedirect->setPath($redirectUrl);
        return $resultRedirect;
    }

    /**
     * @return \makn3tiza\Payulatam\Model\Client\OrderInterface
     */
    protected function getClientOrderHelper()
    {
        return $this->clientFactory->create()->getOrderHelper();
    }
}