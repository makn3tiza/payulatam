<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Block\Payment\Repeat;

class Fail extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \makn3tiza\Payulatam\Helper\Payment
     */
    protected $paymentHelper;

    /**
     * @var \makn3tiza\Payulatam\Model\Session
     */
    protected $session;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \makn3tiza\Payulatam\Model\Session $session,
        \makn3tiza\Payulatam\Helper\Payment $paymentHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->session = $session;
        $this->paymentHelper = $paymentHelper;
    }

    /**
     * @return string|false
     */
    public function getPaymentUrl()
    {
        $orderId = $this->session->getLastOrderId();
        if ($orderId) {
            $repeatPaymentUrl = $this->paymentHelper->getRepeatPaymentUrl($orderId);
            if (!$repeatPaymentUrl) {
                return $this->paymentHelper->getStartPaymentUrl($orderId);
            }
            return $repeatPaymentUrl;
        }
        return false;
    }
}
