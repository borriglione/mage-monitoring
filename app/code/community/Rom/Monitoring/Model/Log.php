<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Model_Log extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('rommonitoring/log');
    }
    
    /**
     * Check if range was already checked today
     * 
     * @param array $range
     * @param Rom_Monitoring_Model_Config $config
     * @return boolean
     */
    public function isRangeWasCheckedToday($range, $config)
    {
        $rangeKey = Mage::helper("rommonitoring/data")->buildRangeKey($range, $config);
        
        $checkRangeCollection = Mage::getModel("rommonitoring/log")
                                    ->getCollection()
                                    ->addFieldToFilter("range_key", $rangeKey)
                                    ->addFieldToFilter(
                                        "execution_day",
                                        date("Y-m-d", Mage::getModel('core/date')->timestamp(time()))
                                    );
        
        if ($checkRangeCollection->count() > 0) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * Save an entry for checked range in database
     * 
     * @param array $range
     * @param int $orderAmount
     * @param Rom_Monitoring_Model_Config $config
     * @return void
     */
    public function saveRange($range, $orderAmount = 0, $config)
    {
        $log = Mage::getModel("rommonitoring/log");
        $log
            ->setRangeKey(Mage::helper("rommonitoring/data")->buildRangeKey($range, $config))
            ->setExecutionDay(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())))
            ->setOrderAmount($orderAmount)
            ->setCreatedAt(now())
            ->setUpdatedAt(now());
        $log->save();
    }
}