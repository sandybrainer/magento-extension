<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

class Ess_M2ePro_Block_Adminhtml_Ebay_Motor_Add_Filter_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_motorsType;

    //########################################

    public function __construct()
    {
        parent::__construct();

        $this->setId('ebayMotorAddTabFilterGrid');

        // Set default values
        //------------------------------
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);
        //------------------------------
    }

    //------------------------------

    protected function _prepareCollection()
    {
        /** @var Ess_M2ePro_Model_Resource_Ebay_Motor_Filter_Collection $collection */
        $collection = Mage::getModel('M2ePro/Ebay_Motor_Filter')->getCollection();
        $collection->addFieldToFilter('type', array('=' => $this->getMotorsType()));

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'title', array(
            'header'       => Mage::helper('M2ePro')->__('Title'),
            'align'        => 'left',
            'type'         => 'text',
            'index'        => 'title',
            'filter_index' => 'title',
            'frame_callback' => array($this, 'callbackColumnTitle')
            )
        );

        $this->addColumn(
            'items', array(
            'header'       => $this->getItemsColumnTitle(),
            'align'        => 'right',
            'type'         => 'text',
            'sortable'     => false,
            'filter'       => false,
            'index'        => 'conditions',
            'frame_callback' => array($this, 'callbackColumnItems')
            )
        );

        $this->addColumn(
            'note', array(
            'header'       => Mage::helper('M2ePro')->__('Note'),
            'align'        => 'left',
            'type'         => 'text',
            'index'        => 'note',
            'filter_index' => 'note'
            )
        );

        $this->addColumn(
            'conditions', array(
            'header'       => Mage::helper('M2ePro')->__('Conditions'),
            'align'        => 'left',
            'type'         => 'text',
            'sortable'     => false,
            'index'        => 'conditions',
            'filter_index' => 'conditions',
            'frame_callback' => array($this, 'callbackColumnConditions'),
            'filter_condition_callback' => array($this, 'callbackFilterConditions')
            )
        );
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->setMassactionIdFieldOnlyIndexValue(true);

        // Set mass-action
        //--------------------------------
        $this->getMassactionBlock()->addItem(
            'select', array(
            'label'   => Mage::helper('M2ePro')->__('Select'),
            'url'     => '',
            'confirm' => Mage::helper('M2ePro')->__('Are you sure?')
            )
        );

        $this->getMassactionBlock()->addItem(
            'setNote', array(
            'label'   => Mage::helper('M2ePro')->__('Set Note'),
            'url'     => '',
            'confirm' => Mage::helper('M2ePro')->__('Are you sure?')
            )
        );

        $this->getMassactionBlock()->addItem(
            'resetNote', array(
            'label'   => Mage::helper('M2ePro')->__('Reset Note'),
            'url'     => '',
            'confirm' => Mage::helper('M2ePro')->__('Are you sure?')
            )
        );

        $this->getMassactionBlock()->addItem(
            'saveAsGroup', array(
            'label'   => Mage::helper('M2ePro')->__('Save As Group'),
            'url'     => '',
            'confirm' => Mage::helper('M2ePro')->__('Are you sure?')
            )
        );

        $this->getMassactionBlock()->addItem(
            'removeFilter', array(
            'label'   => Mage::helper('M2ePro')->__('Remove'),
            'url'     => '',
            'confirm' => Mage::helper('M2ePro')->__('Are you sure?')
            )
        );
        //--------------------------------

        return parent::_prepareMassaction();
    }

    public function getMassactionBlockName()
    {
        // this is required for correct work of massaction js
        return 'M2ePro/adminhtml_grid_massaction';
    }

    //########################################

    public function callbackColumnTitle($value, $row, $column, $isExport)
    {
        return $value;
    }

    public function callbackColumnItems($value, $row, $column, $isExport)
    {
        /** @var Ess_M2ePro_Model_Ebay_Motor_Filter $row */
        $conditions = $row->getConditions();
        $helper = Mage::helper('M2ePro/Component_Ebay_Motors');

        $select = Mage::getResourceModel('core/config')->getReadConnection()
            ->select()
            ->from($helper->getDictionaryTable($this->getMotorsType()));

        $helper = Mage::helper('M2ePro/Component_Ebay_Motors');
        if ($helper->isTypeBasedOnEpids($this->getMotorsType())) {
            $select->where('scope = ?', $helper->getEpidsScopeByType($this->getMotorsType()));
        }

        foreach ($conditions as $key => $value) {
            if ($key != 'year') {
                $select->where('`'.$key.'` LIKE ?', '%'.$value.'%');
                continue;
            }

            if ($row->isTypeEpid()) {
                if (!empty($value['from'])) {
                    $select->where('`year` >= ?', $value['from']);
                }

                if (!empty($value['to'])) {
                    $select->where('`year` <= ?', $value['to']);
                }
            } else {
                if (!empty($value)) {
                    $select->where('from_year <= ?', $value);
                    $select->where('to_year >= ?', $value);
                }
            }
        }

        $itemsCount = $select->query()->rowCount();

        $applyWord = Mage::helper('M2ePro')->__('apply');

        return <<<HTML
<script type="text/javascript">
    ebayMotorsFiltersConditions = typeof ebayMotorsFiltersConditions !== 'undefined' ? ebayMotorsFiltersConditions : {};
    ebayMotorsFiltersConditions[{$row->getId()}] = {$row->getConditions(false)};
</script>
{$itemsCount}
(<a onclick="EbayMotorAddFilterGridHandlerObj.showFilterResult({$row->getId()})"
    href="javascript:void(0)">{$applyWord}</a>)
HTML;
    }

    public function callbackColumnConditions($value, $row, $column, $isExport)
    {
        $conditions = Mage::helper('M2ePro')->jsonDecode($value);

        if (Mage::helper('M2ePro/Component_Ebay_Motors')->isTypeBasedOnEpids($this->getMotorsType())) {
            if (!empty($conditions['year'])) {
                $yearIndex = array_search("year", array_keys($conditions));

                !empty($conditions['year']['to']) && $conditions = array_merge(
                    array_slice($conditions, 0, $yearIndex),
                    array('Year To' => $conditions['year']['to']),
                    array_slice($conditions, $yearIndex)
                );

                !empty($conditions['year']['from']) && $conditions = array_merge(
                    array_slice($conditions, 0, $yearIndex),
                    array('Year From' => $conditions['year']['from']),
                    array_slice($conditions, $yearIndex)
                );

                unset($conditions['year']);
            }

            if (isset($conditions['product_type']) && $conditions['product_type'] != '') {
                switch($conditions['product_type']) {
                    case Ess_M2ePro_Helper_Component_Ebay_Motors::PRODUCT_TYPE_VEHICLE:
                        $conditions['product_type'] = Mage::helper('M2ePro')->__('Car / Truck');
                        break;

                    case Ess_M2ePro_Helper_Component_Ebay_Motors::PRODUCT_TYPE_MOTORCYCLE:
                        $conditions['product_type'] = Mage::helper('M2ePro')->__('Motorcycle');
                        break;

                    case Ess_M2ePro_Helper_Component_Ebay_Motors::PRODUCT_TYPE_ATV:
                        $conditions['product_type'] = Mage::helper('M2ePro')->__('ATV / Snowmobiles');
                        break;
                }
            }
        }

        $html = '<div class="product-options-main" style="font-size: 11px; color: grey; margin-left: 7px">';

        foreach ($conditions as $key => $value) {
            if ($key == 'epid') {
                $key = Mage::helper('M2ePro')->escapeHtml('ePID');
            } else if ($key == 'ktype') {
                $key = Mage::helper('M2ePro')->escapeHtml('kType');
            } else if ($key == 'body_style') {
                $key = Mage::helper('M2ePro')->escapeHtml('Body Style');
            } else if ($key == 'product_type') {
                $key = Mage::helper('M2ePro')->escapeHtml('Type');
            } else if ($key == 'street_name') {
                $key = Mage::helper('M2ePro')->escapeHtml('Street Name');
            } else {
                $key = Mage::helper('M2ePro')->escapeHtml(ucfirst($key));
            }

            $value = Mage::helper('M2ePro')->escapeHtml($value);

            $html .= <<<HTML
<span class="attribute-row">
    <span class="attribute">
        <strong>{$key}</strong>:
    </span>
    <span class="value">{$value}</span>
</span>
<br/>
HTML;
        }

        $html .= '</div>';

        return $html;
    }

    //########################################

    protected function callbackFilterConditions($collection, $column)
    {
        $value = $column->getFilter()->getValue();

        if ($value == null) {
            return;
        }

        $collection->getSelect()->where("conditions LIKE \"%{$value}%\"");
    }

    //########################################

    protected function _toHtml()
    {
        $additionalHtml = <<<HTML
<style type="text/css">
    #{$this->getId()} table td, #{$this->getId()} table th {
        padding: 5px;
    }
</style>
HTML;

        $additionalHtml .= '<script type="text/javascript">';

        if ($this->canDisplayContainer()) {
            $additionalHtml .= <<<JS
EbayMotorAddFilterGridHandlerObj = new EbayMotorAddFilterGridHandler('{$this->getId()}');
JS;
        }

        $additionalHtml .= <<<JS
EbayMotorAddFilterGridHandlerObj.afterInitPage();
EbayMotorAddFilterGridHandlerObj.filtersConditions = typeof ebayMotorsFiltersConditions !== 'undefined' ?
    ebayMotorsFiltersConditions : {};
JS;

        $additionalHtml .= '</script>';

        return '<div style="height: 410px; overflow: auto;">' .
            parent::_toHtml()
            . '</div>' . $additionalHtml;
    }

    //########################################

    public function getGridUrl()
    {
        return $this->getUrl(
            '*/adminhtml_ebay_motor/addFilterGrid', array(
            '_current' => true
            )
        );
    }

    public function getRowUrl($row)
    {
        return false;
    }

    //########################################

    public function setMotorsType($motorsType)
    {
        $this->_motorsType = $motorsType;
    }

    public function getMotorsType()
    {
        if ($this->_motorsType === null) {
            throw new Ess_M2ePro_Model_Exception_Logic('Motors type not set.');
        }

        return $this->_motorsType;
    }

    //########################################

    public function getItemsColumnTitle()
    {
        if (Mage::helper('M2ePro/Component_Ebay_Motors')->isTypeBasedOnEpids($this->getMotorsType())) {
            return Mage::helper('M2ePro')->__('ePID(s)');
        }

        return Mage::helper('M2ePro')->__('kType(s)');
    }

    //########################################
}
