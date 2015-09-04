<?php
/*
* @category   Magazento
* @package    Magazento_Exportpage
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportpage_Block_Admin_Item_Edit_Tab_Tabhoriz_Form extends Mage_Adminhtml_Block_Widget_Form {


    protected function _prepareForm() {
        $model = Mage::registry('exportpage_item');

        $form = new Varien_Data_Form(array('id' => 'edit_form_item', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setHtmlIdPrefix('item_');

        $fieldset = $form->addFieldset('base_fieldset_automation', array('legend' => Mage::helper('exportpage')->__('General settings'), 'class' => 'fieldset-wide'));

        if ($model->getItemId()) {
            $fieldset->addField('item_id', 'hidden', array(
                'name' => 'item_id',
            ));
        }

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('exportpage')->__('Title'),
            'name'  => 'title',
            'required' => true,
        ));

        $fieldset->addField('filename', 'text', array(
            'label' => Mage::helper('exportpage')->__('Filename'),
            'name'  => 'filename',
            'required' => true,
            'note'  => Mage::helper('exportpage')->__('example: export_june (without extension)'),
        ));

        $fieldset->addField('path', 'text', array(
            'label' => Mage::helper('exportpage')->__('Path'),
            'name'  => 'path',
            'required' => true,
            'note'  => Mage::helper('exportpage')->__('example: "export/" or "/" for base path (path must be writeable)'),
        ));



        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('exportpage')->__('Export Filters'), 'class' => 'fieldset-wide'));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'select', array(
                'label'    => Mage::helper('exportpage')->__('Store View'),
                'title'    => Mage::helper('exportpage')->__('Store View'),
                'name'     => 'store_id',
                'required' => false,
                'value'    => $model->getStoreId(),
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(true, false)
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'     => 'store_id',
                'value'    => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $fieldset->addField('time_from', 'date', array(
            'name' => 'time_from',
            'time' => true,
            'label' => Mage::helper('exportpage')->__('From Time'),
            'title' => Mage::helper('exportpage')->__('From Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
        ));

        $fieldset->addField('time_to', 'date', array(
            'name' => 'time_to',
            'time' => true,
            'label' => Mage::helper('exportpage')->__('To Time'),
            'title' => Mage::helper('exportpage')->__('To Time'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format' => $dateFormatIso,
        ));

        $statuses = array(
            array('value' => 'enabled', 'label' => Mage::helper('exportpage')->__('Enabled')),
            array('value' => 'disabled', 'label' => Mage::helper('exportpage')->__('Disabled')),
        );
        $fieldset->addField('status', 'multiselect', array(
            'label'    => Mage::helper('exportpage')->__('Status'),
            'title'    => Mage::helper('exportpage')->__('Status'),
            'name'     => 'status[]',
            'required' => false,
            'values'   => $statuses
        ));






        $fieldset->addField('script_java', 'note', array(
            'text' => '<script type="text/javascript">
				            var inputDateFrom = document.getElementById(\'item_from_time\');
				            var inputDateTo = document.getElementById(\'item_to_time\');
            				inputDateTo.onchange=function(){dateTestAnterior(this)};
				            inputDateFrom.onchange=function(){dateTestAnterior(this)};


				            function dateTestAnterior(inputChanged){
				            	dateFromStr=inputDateFrom.value;
				            	dateToStr=inputDateTo.value;

				            	if(dateFromStr.indexOf(\'.\')==-1)
				            		dateFromStr=dateFromStr.replace(/(\d{1,2} [a-zA-Zâêûîôùàçèé]{3})[^ \.]+/,"$1.");
				            	if(dateToStr.indexOf(\'.\')==-1)
				            		dateToStr=dateToStr.replace(/(\d{1,2} [a-zA-Zâêûîôùàçèé]{3})[^ \.]+/,"$1.");

				            	fromDate= Date.parseDate(dateFromStr,"%e %b %Y %H:%M:%S");
				            	toDate= Date.parseDate(dateToStr,"%e %b %Y %H:%M:%S");

				            	if(dateToStr!=\'\'){
					            	if(fromDate>toDate){
	            						inputChanged.value=\'\';
	            						alert(\'' . Mage::helper('exportpage')->__('You must set a date to value greater than the date from value') . '\');
					            	}
				            	}
            				}
            			</script>',
            'disabled' => true
        ));
        

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
