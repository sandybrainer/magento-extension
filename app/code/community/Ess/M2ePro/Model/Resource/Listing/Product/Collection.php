<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Model_Resource_Listing_Product_Collection
    extends Ess_M2ePro_Model_Resource_Collection_Component_Parent_Abstract
{
    //########################################

    public function _construct()
    {
        parent::_construct();
        $this->_init('M2ePro/Listing_Product');
    }

    //########################################

    public function joinListingTable($columns = array())
    {
        $this->getSelect()->joinLeft(
            array('l' => Mage::getResourceModel('M2ePro/Listing')->getMainTable()),
            'l.id=main_table.listing_id',
            $columns
        );

        return $this;
    }

    //########################################
}
