<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Model\Client\Classic\Order;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use \makn3tiza\Payulatam\Model\Client\Classic\Order;

class Processor
{
    /**
     * @var \makn3tiza\Payulatam\Model\Order\Processor
     */
    protected $orderProcessor;

    public function __construct(
        \makn3tiza\Payulatam\Model\Order\Processor $orderProcessor
    ) {
        $this->orderProcessor = $orderProcessor;
    }

    /**
     * @param string $payulatamOrderId
     * @param string $status
     * @param float $amount
     * @param bool $newest
     * @return bool
     * @throws LocalizedException
     */
    public function processStatusChange($payulatamOrderId, $status = '', $amount = null, $newest = true)
    {
        if (!in_array($status, [
            Order::STATUS_NEW,
            Order::STATUS_PENDING,
            Order::STATUS_CANCELLED,
            Order::STATUS_REJECTED,
            Order::STATUS_WAITING,
            Order::STATUS_REJECTED_CANCELLED,
            Order::STATUS_COMPLETED,
            Order::STATUS_ERROR
        ])
        ) {
            throw new LocalizedException(new Phrase('Invalid status.'));
        }
        if (!$newest) {
            $close = in_array($status, [
                Order::STATUS_CANCELLED,
                Order::STATUS_REJECTED,
                Order::STATUS_COMPLETED
            ]);
            $this->orderProcessor->processOld($payulatamOrderId, $status, $close);
            return true;
        }
        switch ($status) {
            case Order::STATUS_NEW:
            case Order::STATUS_PENDING:
                $this->orderProcessor->processPending($payulatamOrderId, $status);
                return true;
            case Order::STATUS_CANCELLED:
            case Order::STATUS_REJECTED:
            case Order::STATUS_REJECTED_CANCELLED:
            case Order::STATUS_ERROR:
                $this->orderProcessor->processHolded($payulatamOrderId, $status);
                return true;
            case Order::STATUS_WAITING:
                $this->orderProcessor->processWaiting($payulatamOrderId, $status);
                return true;
            case Order::STATUS_COMPLETED:
                $this->orderProcessor->processCompleted($payulatamOrderId, $status, $amount);
                return true;
        }
    }
}
