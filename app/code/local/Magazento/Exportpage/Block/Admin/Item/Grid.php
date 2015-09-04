<?php
/*
* @category   Magazento
* @package    Magazento_Exportpage
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportpage_Block_Admin_Item_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('ExportpageGrid');
        $this->setDefaultSort('item_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('exportpage/item')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $baseUrl = $this->getUrl();

        $this->addColumn('item_id', array(
            'header' => Mage::helper('exportpage')->__('Id'),
            'align' => 'left',
            'width' => '50px',
            'index' => 'item_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('exportpage')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('link', array(
            'header'    => Mage::helper('exportpage')->__('Files '),
            'renderer'  => 'exportpage/admin_item_grid_renderer_link',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('exportpage')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'width'         => '120px',
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'  => array($this, '_filterStoreCondition'),
            ));
        }        


        $this->addColumn('action',
                array(
                    'header' => Mage::helper('exportpage')->__('Action'),
                    'index' => 'item_id',
                    'sortable' => false,
                    'filter' => false,
                    'no_link' => true,
                    'width' => '100px',
                    'renderer' => 'exportpage/admin_item_grid_renderer_action'
        ));
        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection() {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setFormFieldName('massaction');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('exportpage')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('exportpage')->__('Are you sure?')
        ));


        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit',  array('item_id' => $row->getId(), 'type' => $row->getData('item_type')));
    }

}
