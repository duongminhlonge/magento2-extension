<?php

namespace SM\Category\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'navigation_thumbnail', [
            'type'     => 'text',
            'label'    => 'Navigation Thumbnail',
            'input'    => 'image',
            'backend'  => 'SM\Category\Model\Category\Attribute\Backend\NavigationThumbnail',
            'visible'  => true,
            'default'  => '0',
            'required' => false,
            'global'   => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
        ]);
    }
}