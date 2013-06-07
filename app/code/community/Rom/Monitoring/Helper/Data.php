<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Helper_Data extends Mage_Core_Helper_Abstract
{
    const FILENAME = "rommonitoring.xml";
    
    /**
     * Log message
     * 
     * @param string $message
     * @return void
     */
    public function log($message)
    {
        Mage::log($message, null, self::FILENAME, true);
    }
    
    /**
     * Check if current time is in time range
     * 
     * @param array $range
     * @return boolean
     */
    public function isCurrentTimeAfterRangeSlot($range)
    {
        $currentTime = date("H:i:s", Mage::getModel('core/date')->timestamp(time()));
        
        if ($this->getLocalTimestampForTime($currentTime) > $this->getLocalTimestampForTime($range["from_time"])
            && $this->getLocalTimestampForTime($currentTime) > $this->getLocalTimestampForTime($range["to_time"])) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get the timestamp in local time for a given time
     * 
     * @param string $time f.e. 14:00:00
     * @return int
     */
    public function getLocalTimestampForTime($time)
    {
        $currentDay = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
        return strtotime($currentDay." ".$time);
    }
    
    /**
     * Check if range times are valid
     * 
     * @param array $range
     * @return boolean
     */
    public function areRangeTimesValid($range)
    {
        $fromTime = explode(":", $range["from_time"]);
        $toTime = explode(":", $range["to_time"]);
        
        //Check if times have correct formats
        if (count($fromTime) != 2 || count($toTime) != 2) {
            return false;
        }
        
        //Check if from time is before to time
        if ($this->getLocalTimestampForTime($range["from_time"]) >= $this->getLocalTimestampForTime($range["to_time"])) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Build a range key out of from and to time
     * 
     * 
     * @param array $range
     * @return string
     */
    public function buildRangeKey($range)
    {
        $fromTime = str_replace(":", "", $range["from_time"]);
        $toTime = str_replace(":", "", $range["to_time"]);
        
        return $fromTime."_".$toTime."_".$range["count_type"];
    }

}