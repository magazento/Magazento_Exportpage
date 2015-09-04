<?php
/*
* @category   Magazento
* @package    Magazento_Exportpage
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportpage_Block_Admin_Item_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('exportpage_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('exportpage')->__('Page Export Profile'));
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
         

    protected function _beforeToHtml() {
        $this->addTab('form_section_item', array(
            'label' => Mage::helper('exportpage')->__('General information'),
            'title' => Mage::helper('exportpage')->__('General information'),
            'content' => $this->getLayout()->createBlock('exportpage/admin_item_edit_tab_tabhoriz')->toHtml(),
        ));
        
        $this->addTab('related', array(
            'label' => Mage::helper('catalog')->__('Manual Page'),
            'url' => $this->getUrl('*/*/related', array('_current' => true)),
            'class' => 'ajax',
        ));        

        return parent::_beforeToHtml();
    }

}