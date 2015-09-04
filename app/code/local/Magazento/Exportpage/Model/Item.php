<?php
/*
* @category   Magazento
* @package    Magazento_Exportpage
* @author     Magazento
* @copyright  Copyright (c) 2014 Magazento. (http://www.magazento.com)
* @license    Single Use, Limited Licence and Single Use No Resale Licence ["Single Use"]
*/

class Magazento_Exportpage_Model_Item extends Mage_Core_Model_Abstract
{
    const CACHE_TAG     = 'exportpage_item';
    protected $_cacheTag= 'exportpage_item';


    protected function _construct()
    {
        $this->_init('exportpage/item');
    }



}
