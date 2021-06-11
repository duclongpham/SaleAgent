<?php

namespace AHT\SaleAgent\Controller\Custom;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;

class Listorder extends \Magento\Framework\App\Action\Action
{
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultFactory
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
    }
}
