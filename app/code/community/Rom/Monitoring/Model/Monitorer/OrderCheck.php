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
        foreach (Mage::getModel("rommonitoring/config")->getOrderCheckRanges() as $configuredRange) {
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
            
            //Check if order cound matches time range -> if not send an exception mail
            if ((int) $orderCount < (int) $configuredRange["order_count"]) {
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
            ->addFieldToFilter("status", array("in" => Mage::getModel("rommonitoring/config")->getOrderCheckOrderStatus()))
            ->addFieldToFilter(
                "created_at",
                array(
                    "from" => $fromGmtDate,
                    "to" => $toGmtDate
                )
            );
        
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
        $senderKey = Mage::getModel("rommonitoring/config")->getOrderCheckEmailSender();
        $receiverEmails = Mage::getModel("rommonitoring/config")->getOrderCheckEmailReceiver();
        $eMailTemplate = Mage::getModel("rommonitoring/config")->getOrderCheckEmailTemplate();
        $eMailTemplateData = array(
            "day" => date("Y-m-d", Mage::getModel('core/date')->timestamp(time())),
            "from_time" => $configuredRange["from_time"],
            "to_time" => $configuredRange["to_time"],
            "reference_order_amount" => $configuredRange["order_count"],
            "calculated_order_amount" => $orderCount
        );
        
        $this->sendCheckFailedMail($senderKey, $receiverEmails, $eMailTemplate, $eMailTemplateData);
    }

}