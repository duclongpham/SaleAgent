<?php

namespace AHT\SaleAgent\Model\Config\Source;

class ConfigOption implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'name=asc', 'label' => __('Name - A to Z')],
            ['value' => 'name=desc', 'label' => __('Name - Z to A')],
            ['value' => 'price=desc', 'label' => __('Price - High to Low')],
            ['value' => 'price=asc', 'label' => __('Price - Low to High')],
        ];
    }
}
