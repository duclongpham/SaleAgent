<?php

namespace AHT\SaleAgent\Block;

class Index extends \Magento\Framework\View\Element\Template
{
    const XML_CONFIG_SHOW_MAX = 'fast_order/general/show_max';
    const XML_CONFIG_SHOW_ALL = 'fast_order/general/show_all';
    const XML_CONFIG_SORT = 'fast_order/general/sort';

    /**
     * @var array|\Magento\Checkout\Block\Checkout\LayoutProcessorInterface[]
     */
    protected $layoutProcessors;

    /**
     * @param AHT\LoginApi\Helper\Data
     */
    private $helperData;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \AHT\LoginApi\Helper\Data $helperData,
        array $data = [],
        array $layoutProcessors = []
    ) {

        $this->helperData = $helperData;
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
    }

    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }
        return \Zend_Json::encode($this->jsLayout);
    }

    public function getConfig()
    {
        $configs = [
            'show_max' => $this->getShowMax(),
            'sort' => $this->getSort()
        ];
        return $configs;
    }
    public function getConfigValue($field, $storeId = null)
    {
        return $this->_scopeConfig->getValue(
            $field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    public function getShowMax()
    {
        $config = $this->getConfigValue(self::XML_CONFIG_SHOW_MAX);
        $isAll = $this->getConfigValue(self::XML_CONFIG_SHOW_ALL);
        $explode = explode(',', $config);
        foreach ($explode as $v) {
            $tmp[] = [
                'value' => $v,
                'label' => $v
            ];
        }

        if ($isAll == 1) {
            $tmp[] = [
                'value' => -1,
                'label' => __('All')
            ];
        }

        return $tmp;
    }

    public function getSort()
    {
        $label_sort = [
            'name=asc' => __('Name - A to Z'),
            'name=desc' => __('Name - Z to A'),
            'price=desc' => __('Price - High to Low'),
            'price=asc' => __('Price - Low to High'),
        ];

        $config = $this->getConfigValue(self::XML_CONFIG_SORT);
        $explode = explode(',', $config);
        foreach ($explode as $v) {
            if (isset($label_sort[$v])) {
                $tmp[] = [
                    'value' => $v,
                    'label' => $label_sort[$v]
                ];
            }
        }
        return $tmp;
    }
}
