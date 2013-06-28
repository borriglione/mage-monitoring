<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Model_Config_Statistic
{
    /**
     * @var Rom_Monitoring_Model_Config
     */
    protected $config = null;
    
    /**
     * Get statistic of minimum values for configured time ranges
     * 
     * @return array
     */
    public function getMinimumStatistic()
    {
        $minimumRanges = array();
        
        foreach ($this->getConfig()->getOrderCheckRanges() as $range) {
            //Continue if range is invalid
            if (false === Mage::helper("rommonitoring/data")->areRangeTimesValid($range)) {
                continue;
            }
            
            //Build string and got concrete values
            $sql = $this->buildStatisQuery(
                $range,
                "ASC",
                ""
            );
            $statistic = $this->getQueryResult($sql);
            
            //Check for days with 0 orders in this range
            $daysWithZeroOrders = array();
            $expectedDays = $this->getExpectedDays();
            $daysWithOrders = count($statistic);
            if ($expectedDays > $daysWithOrders) {
                $daysWithZeroOrders[] = array(
                    "amount" => 0,
                    "created_at" => ($expectedDays - $daysWithOrders)
                                    .Mage::helper("rommonitoring/data")->__(" day(s)")
                );
            }
            
            //Add range result to result array
            $range["statistic"] = array_merge($daysWithZeroOrders, $statistic);
            $minimumRanges[] = $range;
        }
        
        return $minimumRanges;
    }

    /**
     * Get statistic of minimum values for configured time ranges
     * 
     * @return array
     */
    public function getMaximumStatistic()
    {
        $minimumRanges = array();
        
        foreach ($this->getConfig()->getOrderCheckRanges() as $range) {
            //Continue if range is invalid
            if (false === Mage::helper("rommonitoring/data")->areRangeTimesValid($range)) {
                continue;
            }
            
            $sql = $this->buildStatisQuery(
                $range,
                "DESC"
            );
            
            $range["statistic"] = $this->getQueryResult($sql);
            $minimumRanges[] = $range;
        }
        return $minimumRanges;
    }
    
    /**
     * Build main static query
     * 
     * @param array $range
     * @param string $sortOrder
     * @return string
     * 
     */
    protected function buildStatisQuery($range, $sortOrder = "ASC", $limit = " LIMIT 0 , 10")
    {
        //Build query
        return sprintf(
            "SELECT COUNT(*) as amount, DATE(created_at) as created_at"
            ." FROM %s"
            ." WHERE"
            ." TIME(created_at) > '%s'"
            ." AND TIME(created_at) < '%s'"
            ." AND DATE(created_at) > '%s'"
            ." AND DATE(created_at) < '%s'"
            ." AND status IN ('%s')"
            ." GROUP BY DATE(created_at)"
            ." ORDER BY amount %s"
            ."%s",
            Mage::getSingleton('core/resource')->getTableName('sales/order'),
            $range["from_time"],
            $range["to_time"],
            $this->getConfig()->getStatisticDateFrom(),
            $this->getConfig()->getStatisticDateTo(),
            implode("','", Mage::getModel("rommonitoring/config")->getOrderCheckOrderStatus()),
            $sortOrder,
            $limit
        );
    }
    
    /**
     * Get Query Result
     * 
     * @param string $sql
     * @return array
     */
    protected function getQueryResult($sql)
    {
        $results = array();
        foreach ($this->getReadConnection()->fetchAll($sql) as $result) {
            $results[] = $result;
        }
        return $results;
    }
    
    /**
     * Get Read Connection
     */
    public function getReadConnection()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }
    
    /**
     * Get config model
     * 
     * @return Rom_Monitoring_Model_Config
     */
    protected function getConfig()
    {
        if (true === is_null($this->config)) {
            $this->config = Mage::getModel("rommonitoring/config");
        }
        return $this->config;
    }
    
    /**
     * Get expected days for 2 dates
     * 
     * @return int
     */
    protected function getExpectedDays()
    {
        $datetime1 = new DateTime($this->getConfig()->getStatisticDateFrom());
        $datetime2 = new DateTime($this->getConfig()->getStatisticDateTo());
        $interval = $datetime1->diff($datetime2);
        return $interval->days -1;
    }
}