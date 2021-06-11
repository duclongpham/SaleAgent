<?php

namespace AHT\SaleAgent\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    /**
     * @param \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * @param \Magento\Customer\Model\ResourceModel\Attribute
     */
    private $attributeResource;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Customer\Model\ResourceModel\Attribute $attributeResource
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->attributeResource = $attributeResource;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $attributes = [
            'is_landing' => [
                'type'         => 'int',
                'label'        => 'is landing',
                'input'        => 'select',
                'source'       => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global'       => 1,
                'sort_order'   => 100,
                'visible'      => true,
                'required'     => false,
                'system'       => 0
            ],
        ];

        foreach ($attributes as $key => $value) {
            // $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, $key);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, $key, $value);
        }

        $setup->endSetup();
    }
}
