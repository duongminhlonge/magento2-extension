<?php
namespace SM\IECategory\Block\Adminhtml\Export\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface
{

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Export Category');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Export Category');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('export_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Export Category')]);

        $fieldset->addField(
            'export',
            'button',
            [
                'name' => 'export',
                'label' => __('Category'),
                'title' => __('Category'),
                'value' => 'Export csv file',
                'class' => 'action-default',
                'onclick' => "setLocation('" . $this->getUrl('sm_iecategory/export/exportCsv') . "')",
            ]
        );

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
