<?php

namespace AHT\SaleAgent\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param \Magento\Framework\App\Helper\Context
     */
    private $helperContext;

    /**
     * @param \Magento\Framework\Session\SessionManager
     */
    private $_sessionManager;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $_productCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $helperContext,
        \Magento\Framework\Session\SessionManager $_sessionManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $_productCollectionFactory
    ) {

        $this->helperContext = $helperContext;
        $this->_sessionManager = $_sessionManager;
        $this->_productCollectionFactory = $_productCollectionFactory;
        parent::__construct($helperContext);
    }

    public function saveListOrder($action, $product_id, $qty)
    {
        try {
            $data = $this->getListOrder() ?? [];
            if ($action === 'add') {
                if (!isset($data[$product_id])) {
                    $data[$product_id] = [
                        'id' => $product_id,
                        'qty' => $qty
                    ];
                    $this->_sessionManager->setFastorderList($data);
                    return true;
                }
            } else if ($action === 'remove') {
                if (isset($data[$product_id])) {
                    unset($data[$product_id]);
                    $this->_sessionManager->setFastorderList($data);
                    return true;
                }
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    public function getListOrder()
    {
        return $this->_sessionManager->getFastorderList();
    }

    public function getProductsInListOrder()
    {
        $productCollection = $this->_productCollectionFactory->create();
        $listOrder = $this->getListOrder();

        if (!$listOrder) return [];

        $listKey = array_keys($listOrder);
        $listProduct = $this->_productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', [
                'in' => $listKey
            ])->addPriceData();
        return $listProduct;
    }
}
