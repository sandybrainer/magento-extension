<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */

abstract class Ess_M2ePro_Model_Walmart_Connector_Orders_Cancel_ItemsRequester
    extends Ess_M2ePro_Model_Walmart_Connector_Command_Pending_Requester
{
    // ########################################

    public function getCommand()
    {
        return array('orders','cancel','entities');
    }

    // ########################################

    public function process()
    {
        $this->eventBeforeExecuting();
        $this->getProcessingRunner()->start();
    }

    // ########################################

    protected function getProcessingRunnerModelName()
    {
        return 'Walmart_Connector_Orders_Cancel_ProcessingRunner';
    }

    protected function getProcessingParams()
    {
        return array_merge(
            parent::getProcessingParams(),
            array(
                'request_data' => $this->getRequestData(),
                'order_id'     => $this->params['order']['order_id'],
                'change_id'    => $this->params['order']['change_id'],
                'start_date'   => Mage::helper('M2ePro')->getCurrentGmtDate(),
            )
        );
    }

    // ########################################

    public function getRequestData()
    {
        return $this->params['order']['walmart_order_id'];
    }

    // ########################################
}