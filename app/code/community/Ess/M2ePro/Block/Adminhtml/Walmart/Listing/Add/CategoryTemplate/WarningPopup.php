<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Block_Adminhtml_Walmart_Listing_Add_CategoryTemplate_WarningPopup extends Mage_Adminhtml_Block_Template
{
    //########################################

    public function __construct()
    {
        parent::__construct();

        // Initialization block
        // ---------------------------------------
        $this->setId('walmartListingAddCategoryTemplateWarningPopup');
        // ---------------------------------------

        $this->setTemplate('M2ePro/walmart/listing/add/category_template/warning_popup.phtml');
    }

    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        $data = array(
            'class'   => 'next',
            'label'   => Mage::helper('M2ePro')->__('Continue'),
            'onclick' => 'ListingGridHandlerObj.categoryNotSelectedWarningPopupContinueClick();'
        );
        $this->setChild('continue_button', $this->getLayout()->createBlock('adminhtml/widget_button')->setData($data));

        return $this;
    }

    //########################################
}
