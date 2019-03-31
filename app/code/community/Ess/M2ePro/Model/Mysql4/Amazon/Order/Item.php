<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Model_Mysql4_Amazon_Order_Item
    extends Ess_M2ePro_Model_Mysql4_Abstract
{
    protected $_isPkAutoIncrement = false;

    //########################################

    public function _construct()
    {
        $this->_init('M2ePro/Amazon_Order_Item', 'order_item_id');
        $this->_isPkAutoIncrement = false;
    }

    //########################################
}