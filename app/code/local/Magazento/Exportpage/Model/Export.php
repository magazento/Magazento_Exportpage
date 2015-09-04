<?php
/*
* @category   Magazento
* @package    Magazento_Exportpage
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportpage_Model_Export
{
    private $status = null;
    private $timeFrom = null;
    private $timeTo = null;
    private $items = null;
    private $storeId = null;
    private $path = null;
    private $fileName = null;
    private $log = null;




    /*
     * Loads collection for manually selected items
     * */
    public function loadManualCollection($itemIds) {
        $collection = Mage::getModel('cms/page')->getCollection();
        $collection->addFieldToFilter('page_id', array('in' => $itemIds));
        return $collection;
    }


    /*
     * Get Item list for selected Profile
     * */
    public function loadCollection($profile) {
        // Set Filters
        $this->path = $profile->getPath();
        $this->fileName = $profile->getFilename();
        $this->status = $profile->getStatus();
        $this->timeFrom = $profile->getTimeFrom();
        $this->timeTo = $profile->getTimeTo();
        $this->storeId = $profile->getStoreId(0);
        if ($items = $profile->getRelatedId()) {
            $this->$items = $items ;
        }

        // Items Collection
        $collection = Mage::getModel('cms/page')->getCollection();
        $collection->getSelect()->join(
            array('page_store' => $collection->getTable('cms/page_store')),
            'main_table.page_id = page_store.page_id'
        );
        $collection->getSelect()->where('page_store.store_id IN (?)', 0);

        $statuses = array();
        foreach (explode(',',$this->status) as $v) {
            if ($v == 'enabled') $statuses[] = 1;
            if ($v == 'disabled') $statuses[] = 0;
        }
        if ((count($statuses) == 1)) $collection->addFieldToFilter('is_active',$statuses[0]);

        if ($this->timeFrom)    $collection->addFieldToFilter('creation_time', array('gteq' => $this->timeFrom));
        if ($this->timeTo)      $collection->addFieldToFilter('update_time', array('lteq' => $this->timeTo));

        return $collection;
    }

    /*
     * Export Items
     */
    public function exportItemsForProfile($profileId) {
        $profile = Mage::getModel('exportpage/item')->load($profileId);
        $collection   = $this->loadCollection($profile);

        // Add manual selected items to our collection
        $manualCollection   = $this->loadManualCollection($profile->getData('related_id'));
        foreach ($manualCollection as $manualItem) {
            $found = false;
            foreach ($collection as $item) {
                if ($item->getId() == $manualItem->getId()) {
                    $found = true;
                    continue;
                }
            }
            if (!$found) $collection->addItem($manualItem);
        }

        $total = 0;
        $poArray = new ExSimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Root></Root>');
        foreach ($collection as $item) {
            $total++;
            $this->log = 'Item Id: '.$item->getId().'<br/>';

            $nodeItem       = $poArray->addChild('Item');
            // Item Values
            $nodeValues       = $nodeItem->addChild('ItemValues');
            foreach ($item->getData() as $k => $v) {
                if ($k == 'content') continue;
                $nodeValues->addChild($k, $v);
            }
            $content = $nodeValues->addChild('content');
            $content->addCdata($item->getData('content'));

        }

        $XML = $poArray->asXML();

        $fileName = $this->path.$this->fileName.'.xml';
        $file = Mage::getBaseDir().'/'.$fileName;
        $fileUrl = Mage::app()->getStore(0)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $fileName;
        $fileUrl = '<a target="_blank" href="'.$fileUrl.'">'.$fileUrl.'</a>';

        file_put_contents($file,$XML);

        $result = array(
            'total'=>$total,
            'fileUrl'=>$fileUrl
        );

        return $result;

    }
}

class ExSimpleXMLElement extends SimpleXMLElement
{
    public function addCData($cdata_text)
    {
        $node= dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }
}