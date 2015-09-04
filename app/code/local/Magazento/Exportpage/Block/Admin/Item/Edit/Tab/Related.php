<?php
/*
* @category   Magazento
* @package    Magazento_Exportpage
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportpage_Block_Admin_Item_Edit_Tab_Related extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
       
        parent::__construct();
        $this->setId('related');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {

        $collection = Mage::getModel('cms/page')->getCollection();
        $this->setCollection($collection);
//        var_dump($collection->getData());
        return parent::_prepareCollection();
    }
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    protected function _prepareColumns() {
         $this->addColumn('in_related', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'field_name'=> 'related_list[]',
            'values'    => $this->_getSelectedItems(),
            'align'     => 'center',
            'index'     => 'page_id'
        ));

        $this->addColumn('page_id', array(
            'header'=> Mage::helper('sales')->__('ID'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'page_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('cms')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('identifier', array(
            'header'    => Mage::helper('cms')->__('Identifier'),
            'align'     => 'left',
            'index'     => 'identifier'
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('cms')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('cms')->__('Disabled'),
                1 => Mage::helper('cms')->__('Enabled')
            ),
        ));

        $this->addColumn('creation_time', array(
            'header'    => Mage::helper('cms')->__('Date Created'),
            'index'     => 'creation_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('cms')->__('Last Modified'),
            'index'     => 'update_time',
            'type'      => 'datetime',
        ));



        return parent::_prepareColumns();
    }

  public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/relatedGrid', array('_current' => true));
    }


  protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_related') {
            $relatedIds = $this->_getSelectedItems();
            if (empty($relatedIds)) {
                $relatedIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$relatedIds));
            } else {
                if($relatedIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$relatedIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    
    protected function _getSelectedItems()
    {
//        var_dump($this->getRequest()->getPost());
//        exit();
//        $relatedItems = $this->getRequest()->getPost('products', null);
//
//        if (!is_array($relatedItems)) {
            $id = Mage::app()->getFrontController()->getRequest()->get('item_id');
            $model = Mage::getModel('exportpage/item')->load($id);
            $relatedItems = $model->getData('related_id');
            
//        }
//        var_dump($relatedItems);
        
        return $relatedItems;
    }    
 
}

?>
