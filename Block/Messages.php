<?php
/**
 * @copyright makn3tiza_
 */

namespace makn3tiza\Payulatam\Block;

class Messages extends \Magento\Framework\View\Element\Messages
{
    protected function _prepareLayout()
    {
        $this->addMessages($this->messageManager->getMessages(true));
        return parent::_prepareLayout();
    }
}
