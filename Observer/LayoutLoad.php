<?php

namespace AHT\SaleAgent\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\LayoutInterface;

class LayoutLoad implements ObserverInterface
{

    /**
     * @param \Magento\Framework\Registry
     */
    private $registry;

    public function __construct(
        \Magento\Framework\Registry $registry
    ) {
        $this->registry = $registry;
    }
    public function execute(Observer $observer)
    {
        $layout = $observer->getEvent()->getLayout();
        $listLayout = $layout->getUpdate()->getHandles();

        if (array_search('catalog_category_view', $listLayout)) {
            $current_category = $this->registry->registry('current_category');
            if ($current_category->getData('is_landing') == 1) {
                $layout->getUpdate()->addHandle("here_is_namelayout");
            }
        }
    }
}
