<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Model\Order;

class ExtOrderId
{
    /**
     * @var \makn3tiza\Payulatam\Model\ResourceModel\Transaction
     */
    protected $transactionResource;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @param \makn3tiza\Payulatam\Model\ResourceModel\Transaction $transactionResource
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     */
    public function __construct(
        \makn3tiza\Payulatam\Model\ResourceModel\Transaction $transactionResource,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        $this->transactionResource = $transactionResource;
        $this->dateTime = $dateTime;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    public function generate(\Magento\Sales\Model\Order $order)
    {
        $try = $this->transactionResource->getLastTryByOrderId($order->getId()) + 1;
        return $order->getIncrementId() . ':' . $this->dateTime->timestamp() . ':' . $try;
    }
}
