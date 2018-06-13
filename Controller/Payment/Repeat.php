<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Controller\Payment;

class Repeat extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Action\Context
     */
    protected $context;

    /**
     * @var \makn3tiza\Payulatam\Helper\Payment
     */
    protected $paymentHelper;

    /**
     * @var \makn3tiza\Payulatam\Model\Session
     */
    protected $session;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \makn3tiza\Payulatam\Helper\Payment $paymentHelper,
        \makn3tiza\Payulatam\Model\Session $session
    ) {
        parent::__construct($context);
        $this->context = $context;
        $this->paymentHelper = $paymentHelper;
        $this->session = $session;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $payulatamOrderId = $this->context->getRequest()->getParam('id');
        $orderId = $this->paymentHelper->getOrderIdIfCanRepeat($payulatamOrderId);
        if ($orderId) {
            $resultRedirect->setPath('orba_payupl/payment/repeat_start');
            $this->session->setLastOrderId($orderId);
        } else {
            $resultRedirect->setPath('orba_payupl/payment/repeat_error');
            $this->messageManager->addError(__('The repeat payment link is invalid.'));
        }
        return $resultRedirect;
    }
}
