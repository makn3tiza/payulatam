<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Payment extends AbstractHelper
{
    /**
     * @var \makn3tiza\Payulatam\Model\ResourceModel\Transaction
     */
    protected $transactionResource;

    /**
     * @var \makn3tiza\Payulatam\Model\Order
     */
    protected $orderHelper;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \makn3tiza\Payulatam\Model\ResourceModel\Transaction $transactionResource
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \makn3tiza\Payulatam\Model\ResourceModel\Transaction $transactionResource,
        \makn3tiza\Payulatam\Model\Order $orderHelper
    ) {
        parent::__construct($context);
        $this->transactionResource = $transactionResource;
        $this->orderHelper = $orderHelper;
    }

    /**
     * @param int $orderId
     * @return string|false
     */
    public function getStartPaymentUrl($orderId)
    {
        $order = $this->orderHelper->loadOrderById($orderId);
        if ($order && $this->orderHelper->canStartFirstPayment($order)) {
            return $this->_urlBuilder->getUrl('payulatam/payment/start', ['id' => $orderId]);
        }
        return false;
    }

    /**
     * @param int $orderId
     * @return string|false
     */
    public function getRepeatPaymentUrl($orderId)
    {
        $order = $this->orderHelper->loadOrderById($orderId);
        if ($order && $this->orderHelper->canRepeatPayment($order)) {
            return $this->_urlBuilder->getUrl(
                'payulatam/payment/repeat',
                ['id' => $this->transactionResource->getLastPayuplOrderIdByOrderId($orderId)]
            );
        }
        return false;
    }

    /**
     * @param string $payulatamOrderId
     * @return bool
     */
    public function getOrderIdIfCanRepeat($payulatamOrderId = null)
    {
        if ($payulatamOrderId && $this->transactionResource->checkIfNewestByPayuplOrderId($payulatamOrderId)) {
            return $this->transactionResource->getOrderIdByPayuplOrderId($payulatamOrderId);
        }
        return false;
    }
}
