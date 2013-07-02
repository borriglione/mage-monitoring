<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Model_Monitorer_OrderCheck extends Rom_Monitoring_Model_Monitorer_Abstract
{
    /**
     * General check range function
     * 
     * @return void
     */
    public function check()
    {
        /**
         * Configured Range Format
         * 
         * [_1369914393782_782] => Array
         * (
         *    [from_time] => 00:00
         *    [to_time] => 06:00
         *    [order_count] => 2
         * )
         */
        foreach ($this->getConfig()->getOrderCheckRanges() as $configuredRange) {
            //Logging
            Mage::helper("rommonitoring/data")->log(
                "Start to process configured time range: ".var_export($configuredRange, true)
            );
            
            //Check if the range could be checked
            if (false === $this->rangeCanBeChecked($configuredRange)) {
                continue;
            }
            
            //Get Order count for time range
            $orderCount = $this->getOrderCountForTimeRange($configuredRange);
            
            //Range Check
            if (true === $this->isRangeCheckFailed($configuredRange, $orderCount)) {
                $this->sendOrderCheckFailedMail($configuredRange, $orderCount);
            }
            
            //Save range as checked
            $this->getLogHandler()->saveRange($configuredRange, $orderCount);
        }
    }
    
    /**
     * Check if a range can be checked
     * 
     * @param array $range
     * @return boolean
     */
    public function rangeCanBeChecked($range)
    {
        //Check if from and to time are valid
        if (false === Mage::helper("rommonitoring/data")->areRangeTimesValid($range)) {
            Mage::helper("rommonitoring/data")->log("Configured times are not valid");
            return false;
        }
        
        //Check if current time is after the time range
        if (false === Mage::helper("rommonitoring/data")->isCurrentTimeAfterRangeSlot($range)) {
            Mage::helper("rommonitoring/data")->log("Configured time range is not over today yet");
            return false;
        }
        
        //Check if range was not checked yet today
        if (false === $this->getLogHandler()->isRangeWasCheckedToday($range)) {
            Mage::helper("rommonitoring/data")->log("Range was already checked today");
            return false;
        }
        
        return true;
    }
    
    /**
     * Get the order amount for a time range
     * 
     * @param array $range
     * @return int
     */
    public function getOrderCountForTimeRange($range)
    {
        $fromGmtDate = Mage::getModel('core/date')->gmtDate(
            null,
            Mage::helper("rommonitoring/data")->getLocalTimestampForTime($range["from_time"])
        );
        $toGmtDate = Mage::getModel('core/date')->gmtDate(
            null,
            Mage::helper("rommonitoring/data")->getLocalTimestampForTime($range["to_time"])
        );
        
        $orderCollection = Mage::getModel("sales/order")
            ->getCollection()
            ->addFieldToFilter("status", array("in" => $this->getConfig()->getOrderCheckOrderStatus()))
            ->addFieldToFilter(
                "created_at",
                array(
                    "from" => $fromGmtDate,
                    "to" => $toGmtDate
                )
            );
        $storeIdFilter = $this->getStoreIdFilter();
        if (true == is_array($storeIdFilter) && count($storeIdFilter) > 0) {
            $orderCollection->addFieldToFilter("store_id", array("in" => $storeIdFilter));
        }
        
        return count($orderCollection);
    }
    
    /**
     * Send email in case of failed order check
     * 
     * @param array $range
     * @param int   $orderCount
     * @return void
     */
    public function sendOrderCheckFailedMail($configuredRange, $orderCount)
    {
        $senderKey = $this->getConfig()->getOrderCheckEmailSender();
        $receiverEmails = $this->getConfig()->getOrderCheckEmailReceiver();
        
        //Get correct alert mail template
        if ($configuredRange["count_type"] == Rom_Monitoring_Model_System_Config_Source_CountType::COUNT_TYPE_MIN) {
            $eMailTemplate = $this->getConfig()->getOrderCheckEmailTemplateMinimum();
        } elseif ($configuredRange["count_type"] == Rom_Monitoring_Model_System_Config_Source_CountType::COUNT_TYPE_MAX) {
            $eMailTemplate = $this->getConfig()->getOrderCheckEmailTemplateMaximum();
        }
        
        $eMailTemplateData = array(
            "day" => date("Y-m-d", Mage::getModel('core/date')->timestamp(time())),
            "from_time" => $configuredRange["from_time"],
            "to_time" => $configuredRange["to_time"],
            "reference_order_amount" => $configuredRange["order_count"],
            "calculated_order_amount" => $orderCount,
            "scope" => $this->getConfig()->type
        );
        
        $this->sendCheckFailedMail($senderKey, $receiverEmails, $eMailTemplate, $eMailTemplateData);
    }
    
    /**
     * Check time range by calculated order count
     * 
     * @param array $range
     * @param int   $orderCount
     * @return boolean
     */
    public function isRangeCheckFailed($configuredRange, $orderCount)
    {
        //Check if order cound matches time range -> if not send an exception mail
        if ($configuredRange["count_type"] == Rom_Monitoring_Model_System_Config_Source_CountType::COUNT_TYPE_MIN) {
            if ((int) $orderCount < (int) $configuredRange["order_count"]) {
                return true;
            }
            return false;
        } elseif ($configuredRange["count_type"] == Rom_Monitoring_Model_System_Config_Source_CountType::COUNT_TYPE_MAX) {
            if ((int) $orderCount > (int) $configuredRange["order_count"]) {
                return true;
            }
            return false;
        } else {
            Mage::throwException(
                Mage::helper('rommonitoring')->__('Unknown count type "%s" for time range detected', $configuredRange["count_type"])
            );
            
        }
    }
}