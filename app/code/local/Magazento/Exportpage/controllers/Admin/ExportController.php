<?php
/*
* @category   Magazento
* @package    Magazento_Exportpage
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportpage_Admin_ExportController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('system/exportpage')
                ->_addBreadcrumb(Mage::helper('exportpage')->__('exportpage'), Mage::helper('exportpage')->__('exportpage'))
                ->_addBreadcrumb(Mage::helper('exportpage')->__('exportpage Items'), Mage::helper('exportpage')->__('exportpage Items'))
        ;
        return $this;
    }


    public function indexAction() {

        if ($id = $this->getRequest()->getParam('item_id')) {

            try {
                $result = Mage::getModel('exportpage/export')->exportItemsForProfile($id);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('exportpage')->__('Items exported: %s', $result['total']));
                Mage::getSingleton('adminhtml/session')->addSuccess($result['fileUrl']);
                $this->_redirect('*/admin_item/index');

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/admin_item/index');
                return;
            }
        }
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('exportpage/item');
    }

}