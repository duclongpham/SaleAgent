<?php

namespace AHT\SaleAgent\Block\Fastorder;

class ListOrder extends \Magento\Framework\View\Element\Template
{
    private $sessionListOrder;

    /**
     * @param \AHT\SaleAgent\Helper\Data
     */
    private $helperData;

    /**
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param \Magento\Catalog\Helper\Image
     */
    private $imageHelper;

    /**
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    private $stockItemRepository;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \AHT\SaleAgent\Helper\Data $helperData,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        array $data = []
    ) {

        $this->helperData = $helperData;
        $this->sessionListOrder = $helperData->getListOrder();
        $this->priceCurrency = $priceCurrency;
        $this->imageHelper = $imageHelper;
        $this->stockItemRepository = $stockItemRepository;
        parent::__construct($context, $data);
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
     * @param \Magento\Catalog\Model\Product
     */
    public function getUrlImage(\Magento\Catalog\Model\Product $product)
    {
        return $this->imageHelper
            ->init($product, 'product_base_image')
            ->getUrl();
    }

    /**
     * @param \Magento\Catalog\Model\Product
     */
    public function getMaxStock(\Magento\Catalog\Model\Product $product)
    {
        return $this->stockItemRepository->get($product->getId())->getMaxSaleQty();
    }

    public function getProductOrder()
    {
        return $this->helperData->getProductsInListOrder();
    }

    /**
     * @param \Magento\Catalog\Model\Product
     */
    public function getQty(\Magento\Catalog\Model\Product $product)
    {
        $listOrder = $this->sessionListOrder;
        $qty = 1;
        if (isset($listOrder[$product->getId()])) {
            if (isset($listOrder[$product->getId()]['qty'])) {
                $qty = $listOrder[$product->getId()]['qty'];
            }
        }
        return $qty;
    }
}
