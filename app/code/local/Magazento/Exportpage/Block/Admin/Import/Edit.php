<?php
/*
* @category   Magazento
* @package    Magazento_Exportpage
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportpage_Block_Admin_Import_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId = 'item_id';
        $this->_controller = 'admin_import';
        $this->_blockGroup = 'exportpage';

        parent::__construct();
        $this->_removeButton('back');
        $this->_removeButton('reset');
        $this->_removeButton('delete');


        $this->_updateButton('save', 'label', Mage::helper('exportpage')->__('Import File'));

    }

    public function getHeaderText()
    {
        return Mage::helper('exportpage')->__("Import from XML file");
    }

}
