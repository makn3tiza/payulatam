<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Block\Payment\Info;

class Buttons extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'payment/info/buttons.phtml';

    public function getOrderId()
    {
        return $this->getParentBlock()->getInfo()->getOrder()->getId();
    }
}
