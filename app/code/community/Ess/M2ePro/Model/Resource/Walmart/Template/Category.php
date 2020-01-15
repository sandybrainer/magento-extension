<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Model_Resource_Walmart_Template_Category
    extends Ess_M2ePro_Model_Resource_Abstract
{
    protected $_isPkAutoIncrement = false;

    //########################################

    public function _construct()
    {
        $this->_init('M2ePro/Walmart_Template_Category', 'id');
        $this->_isPkAutoIncrement = false;
    }

    //########################################
}
