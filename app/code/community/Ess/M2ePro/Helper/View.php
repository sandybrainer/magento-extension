<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

use Ess_M2ePro_Helper_View_Ebay_Component as EbayComponent;
use Ess_M2ePro_Helper_View_Amazon_Component as AmazonComponent;
use Ess_M2ePro_Helper_View_Walmart_Component as WalmartComponent;

use Ess_M2ePro_Helper_View_Ebay_Controller as EbayControllerComponent;
use Ess_M2ePro_Helper_View_Amazon_Controller as AmazonControllerComponent;
use Ess_M2ePro_Helper_View_Walmart_Controller as WalmartControllerComponent;

class Ess_M2ePro_Helper_View extends Mage_Core_Helper_Abstract
{
    const LAYOUT_NICK = 'm2epro';
    const GENERAL_BLOCK_PATH = 'M2ePro/adminhtml_general';

    const LISTING_CREATION_MODE_FULL = 0;
    const LISTING_CREATION_MODE_LISTING_ONLY = 1;

    const MOVING_LISTING_OTHER_SELECTED_SESSION_KEY = 'moving_listing_other_selected';
    const MOVING_LISTING_PRODUCTS_SELECTED_SESSION_KEY = 'moving_listing_products_selected';

    //########################################

    public function isBaseControllerLoaded()
    {
        return (bool)Mage::helper('M2ePro/Data_Global')->getValue('is_base_controller_loaded');
    }

    //########################################

    /**
     * @param string $viewNick
     * @return Ess_M2ePro_Helper_View_Amazon|Ess_M2ePro_Helper_View_Ebay|Ess_M2ePro_Helper_View_Walmart
     */
    public function getHelper($viewNick = null)
    {
        if (is_null($viewNick)) {
            $viewNick = $this->getCurrentView();
        }

        switch ($viewNick) {
            case Ess_M2ePro_Helper_View_Ebay::NICK:
                $helper = Mage::helper('M2ePro/View_Ebay');
                break;

            case Ess_M2ePro_Helper_View_Amazon::NICK:
                $helper = Mage::helper('M2ePro/View_Amazon');
                break;

            case Ess_M2ePro_Helper_View_Walmart::NICK:
                $helper = Mage::helper('M2ePro/View_Walmart');
                break;

            default:
                $helper = Mage::helper('M2ePro/View_Amazon');
                break;
        }

        return $helper;
    }

    /**
     * @param string $viewNick
     * @return AmazonComponent|EbayComponent|WalmartComponent
     */
    public function getComponentHelper($viewNick = null)
    {
        if (is_null($viewNick)) {
            $viewNick = $this->getCurrentView();
        }

        switch ($viewNick) {
            case Ess_M2ePro_Helper_View_Ebay::NICK:
                $helper = Mage::helper('M2ePro/View_Ebay_Component');
                break;

            case Ess_M2ePro_Helper_View_Amazon::NICK:
                $helper = Mage::helper('M2ePro/View_Amazon_Component');
                break;

            case Ess_M2ePro_Helper_View_Walmart::NICK:
                $helper = Mage::helper('M2ePro/View_Walmart_Component');
                break;

            default:
                $helper = Mage::helper('M2ePro/View_Amazon_Component');
                break;
        }

        return $helper;
    }

    /**
     * @param string $viewNick
     * @return AmazonControllerComponent|EbayControllerComponent|WalmartControllerComponent
     */
    public function getControllerHelper($viewNick = null)
    {
        if (is_null($viewNick)) {
            $viewNick = $this->getCurrentView();
        }

        switch ($viewNick) {
            case Ess_M2ePro_Helper_View_Ebay::NICK:
                $helper = Mage::helper('M2ePro/View_Ebay_Controller');
                break;

            case Ess_M2ePro_Helper_View_Amazon::NICK:
                $helper = Mage::helper('M2ePro/View_Amazon_Controller');
                break;

            case Ess_M2ePro_Helper_View_Walmart::NICK:
                $helper = Mage::helper('M2ePro/View_Walmart_Controller');
                break;

            default:
                $helper = Mage::helper('M2ePro/View_Amazon_Controller');
                break;
        }

        return $helper;
    }

    //########################################

    public function getCurrentView()
    {
        $request = Mage::app()->getRequest();
        $controller = $request->getControllerName();

        if (is_null($controller)) {
            return NULL;
        }

        if (stripos($controller, 'adminhtml_ebay') !== false) {
            return Ess_M2ePro_Helper_View_Ebay::NICK;
        }

        if (stripos($controller, 'adminhtml_amazon') !== false) {
            return Ess_M2ePro_Helper_View_Amazon::NICK;
        }

        if (stripos($controller, 'adminhtml_walmart') !== false) {
            return Ess_M2ePro_Helper_View_Walmart::NICK;
        }

        if (stripos($controller, 'adminhtml_development') !== false) {
            return Ess_M2ePro_Helper_View_Development::NICK;
        }

        if (stripos($controller, 'adminhtml_wizard') !== false) {
            return Ess_M2ePro_Helper_View_Wizard::NICK;
        }

        if (stripos($controller, 'system_config') !== false) {
            return Ess_M2ePro_Helper_View_Configuration::NICK;
        }

        return NULL;
    }

    // ---------------------------------------

    public function isCurrentViewEbay()
    {
        return $this->getCurrentView() == Ess_M2ePro_Helper_View_Ebay::NICK;
    }

    public function isCurrentViewAmazon()
    {
        return $this->getCurrentView() == Ess_M2ePro_Helper_View_Amazon::NICK;
    }

    public function isCurrentViewWalmart()
    {
        return $this->getCurrentView() == Ess_M2ePro_Helper_View_Walmart::NICK;
    }

    public function isCurrentViewDevelopment()
    {
        return $this->getCurrentView() == Ess_M2ePro_Helper_View_Development::NICK;
    }

    public function isCurrentViewConfiguration()
    {
        return $this->getCurrentView() == Ess_M2ePro_Helper_View_Configuration::NICK;
    }

    public function isCurrentViewIntegration()
    {
        return $this->isCurrentViewEbay() || $this->isCurrentViewAmazon() || $this->isCurrentViewWalmart();
    }

    // ---------------------------------------

    public function isCurrentViewWizard()
    {
        return $this->getCurrentView() == Ess_M2ePro_Helper_View_Wizard::NICK;
    }

    //########################################

    public function getUrl($row, $controller, $action, array $params = array())
    {
        $component = strtolower($row->getData('component_mode'));
        return Mage::helper('adminhtml')->getUrl("*/adminhtml_{$component}_{$controller}/{$action}", $params);
    }

    public function getModifiedLogMessage($logMessage)
    {
        $description = Mage::helper('M2ePro/Module_Log')->decodeDescription($logMessage);

        preg_match_all('/[^(href=")](http|https)\:\/\/[a-z0-9\-\._\/+\?\&\%=;\(\)]+/i', $description, $matches);
        $matches = array_unique($matches[0]);

        foreach ($matches as &$url) {
            $url = trim($url, '.()[] ');
        }
        unset($url);

        foreach ($matches as $url) {

            $nestingLinks = 0;
            foreach ($matches as $value) {
                if (strpos($value, $url) !== false) {
                    $nestingLinks++;
                }
            }

            if ($nestingLinks > 1) {
                continue;
            }

            $description = str_replace($url, "<a target=\"_blank\" href=\"{$url}\">{$url}</a>", $description);
        }

        return Mage::helper('M2ePro')->escapeHtml($description, array('a'), ENT_NOQUOTES);
    }

    //########################################

    public function getMenuPath(SimpleXMLElement $parentNode, $pathNick, $rootMenuLabel = '')
    {
        $paths = $this->getMenuPaths($parentNode, $rootMenuLabel);

        $preparedPath = preg_quote(trim($pathNick, '/'), '/');

        $resultLabels = array();
        foreach ($paths as $pathNick => $label) {
            if (preg_match('/'.$preparedPath.'\/?$/', $pathNick)) {
                $resultLabels[] = $label;
            }
        }

        if (empty($resultLabels)) {
            return '';
        }

        if (count($resultLabels) > 1) {
            throw new Ess_M2ePro_Model_Exception('More than one menu path found');
        }

        return array_shift($resultLabels);
    }

    public function getMenuPaths(SimpleXMLElement $parentNode, $parentLabel = '', $parentPath = '')
    {
        if (empty($parentNode->children)) {
            return '';
        }

        $paths = array();

        foreach ($parentNode->children->children() as $key => $child) {
            $path  = '/'.$key.'/';
            if (!empty($parentPath)) {
                $path = '/'.trim($parentPath, '/').$path;
            }

            $label = Mage::helper('M2ePro')->__((string)$child->title);
            if (!empty($parentLabel)) {
                $label = $parentLabel.' > '.$label;
            }

            $paths[$path] = $label;

            if (empty($child->children)) {
                continue;
            }

            $paths = array_merge($paths, $this->getMenuPaths($child, $label, $path));
        }

        return $paths;
    }

    // ---------------------------------------

    public function getPageNavigationPath($pathNick, $tabName = NULL, $additionalEnd = NULL)
    {
        $resultPath = array();

        $rootMenuNode = Mage::getConfig()->getNode('adminhtml/menu/m2epro');
        $menuLabel = Mage::helper('M2ePro/View')->getMenuPath(
            $rootMenuNode, $pathNick,
            Mage::helper('M2ePro/Component')->isSingleActiveComponent() ? '' : $rootMenuNode->title
        );

        if (!$menuLabel) {
            return '';
        }

        $resultPath['menu'] = $menuLabel;

        if ($tabName) {
            $resultPath['tab'] = Mage::helper('M2ePro')->__($tabName) . ' ' . Mage::helper('M2ePro')->__('Tab');
        }

        if ($additionalEnd) {
            $resultPath['additional'] = Mage::helper('M2ePro')->__($additionalEnd);
        }

        return join($resultPath, ' > ');
    }

    //########################################
}