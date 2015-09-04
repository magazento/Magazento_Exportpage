<?php
/*
* @category   Magazento
* @package    Magazento_Exportpage
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportpage_Block_Admin_Item extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    
    public function __construct()
    {
        
        $this->_controller = 'admin_item';
        $this->_blockGroup = 'exportpage';
        $this->_headerText = Mage::helper('exportpage')->__('Page Export Profiles');
        $this->_addButtonLabel = Mage::helper('exportpage')->__('Add Profile');
        parent::__construct();

        $this->setTemplate('widget/grid/container.phtml');
    }
    
}
