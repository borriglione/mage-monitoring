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
    const FILENAME = "rommonitoring.log";
    
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
     * @param array $range
     * @param Rom_Monitoring_Model_Config $config
     * @return string
     */
    public function buildRangeKey($range, $config)
    {
        $fromTime = str_replace(":", "", $range["from_time"]);
        $toTime = str_replace(":", "", $range["to_time"]);
        
        $key = $fromTime."_".$toTime."_".$range["count_type"]."_".$config->type;
        
        if ($config->isTypeWebsite()) {
            $key .= "_".$config->websiteId;
        }
        
        if ($config->isTypeStore()) {
            $key .= "_".$config->storeId;
        }
        
        return $key;
    }
    
    /**
     * Define scope -> global, website or store
     * 
     * @param Rom_Monitoring_Model_Config
     * @param string $scope
     * @return Rom_Monitoring_Model_Config
     */
    public function defineScope($config, $scope)
    {
        if (false !== strpos($scope, Rom_Monitoring_Model_System_Config_Source_Scopes::KEY_GLOBAL)) {
            return $config->setTypeGlobal();
        } elseif (false !== strpos($scope, Rom_Monitoring_Model_System_Config_Source_Scopes::KEY_WEBSITE)) {
            $websiteId = str_replace(Rom_Monitoring_Model_System_Config_Source_Scopes::KEY_WEBSITE, "", $scope);
            return $config->setTypeWebsite($websiteId);
        } elseif (false !== strpos($scope, Rom_Monitoring_Model_System_Config_Source_Scopes::KEY_STORE)) {
            $websiteId = str_replace(Rom_Monitoring_Model_System_Config_Source_Scopes::KEY_STORE, "", $scope);
            return $config->setTypeStore($websiteId);
        }
        
        return null;
    }

}