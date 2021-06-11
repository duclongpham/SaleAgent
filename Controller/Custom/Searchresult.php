<?php

namespace AHT\SaleAgent\Controller\Custom;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;

class Searchresult extends \Magento\Framework\App\Action\Action
{
    /**
     * @param \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry
    ) {

        $this->registry = $registry;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->registry->register(
            'search',
            $this->_request->getParam('q')
        );

        $this->registry->register(
            'sort',
            $this->_request->getParam('sort')
        );

        $this->registry->register(
            'show_max',
            $this->_request->getParam('show_max')
        );
        return $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
    }
}
