<?php

namespace AHT\SaleAgent\Block\Fastorder;

class SearchResult extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @param \Magento\Catalog\Helper\Image
     */
    private $imageHelper;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {

        $this->registry = $registry;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->imageHelper = $imageHelper;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
    }

    public function getProduct()
    {
        $search = $this->registry->registry('search');
        $sort = $this->registry->registry('sort');
        $show_max = $this->registry->registry('show_max');

        if (!$search) return [];

        return $this->searchByName($search, $sort, $show_max);
    }

    public function searchByName($search, $sort, $show_max)
    {
        $data = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('name', [
                'like' => '%' . $search . '%'
            ])
            ->addAttributeToFilter('visibility', ['in' => [3, 4]])
            ->addPriceData()
            ->setStoreId($this->getStoreId());
        if ($sort != -1) {
            $explode = explode('=', $sort);
            if ($explode[0] == 'price') {
                $data = $data->setOrder('price', $explode[1] === 'asc' ? 'ASC' : 'DESC');
            } elseif ($explode[0] == 'name') {
                $data = $data->setOrder('name', $explode[1] === 'asc' ? 'ASC' : 'DESC');
            }
        }
        if (is_numeric($show_max) && $show_max > 0) {
            $data = $data->setPageSize($show_max)
                ->setCurPage(1);
        }

        foreach ($data as &$value) {
            $value['src'] = $this->imageHelper
                ->init($value, 'product_base_image')
                ->getUrl();
        }
        return $data;
    }
    public function getSymbol()
    {
        return $this->priceCurrency->getCurrencySymbol();
    }

    protected function formatPrice(string $price): string
    {
        return (string) number_format($price, 2, '.', "");
    }

    public function getPrice($product)
    {
        if ($product->getType_id() === 'configurable' || $product->getType_id() === 'grouped') {
            return $this->formatPrice($product->getData('min_price'));
        } else if ($product->getType_id() === 'bundle') {
            return $this->formatPrice($product->getData('min_price')) .
                ' ' . __('To') . ' ' .
                $this->formatPrice($product->getData('max_price'));
        }

        return $this->formatPrice($product->getData('final_price'));
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
