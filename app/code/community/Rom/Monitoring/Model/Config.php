<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Model_Config
{
    /**
     * @var string
     */
    const TYPE_GLOBAL = "global";
    
    /**
     * @var string
     */
    const TYPE_WEBSITE = "website";
    
    /**
     * @var string
     */
    const TYPE_STORE = "store";
    
    /**
     * @var mixed
     */
    public $type = self::TYPE_GLOBAL;
    
    /**
     * @var int
     */
    public $storeId = null;
    
    /**
     * @var int
     */
    public $websiteId = null;
    
    /**
     * Set config model type global
     * 
     * @return Rom_Monitoring_Model_Config
     */
    public function setTypeGlobal()
    {
        $this->type = self::TYPE_GLOBAL;
        return $this;
    }
    
    /**
     * Set config model type website
     * 
     * @param int $websiteId
     * @return Rom_Monitoring_Model_Config
     */
    public function setTypeWebsite($websiteId)
    {
        $this->type = self::TYPE_WEBSITE;
        $this->websiteId = $websiteId;
        return $this;
    }
    
    /**
     * Set config model type store
     * 
     * @param int $storeId
     * @return Rom_Monitoring_Model_Config
     */
    public function setTypeStore($storeId)
    {
        $this->type = self::TYPE_STORE;
        $this->storeId = $storeId;
        return $this;
    }
    
    /**
     * Check if type is global
     * 
     * @return boolean
     */
    public function isTypeGlobal()
    {
        return ($this->type == self::TYPE_GLOBAL);
    }
    
    /**
     * Check if type is website
     * 
     * @return boolean
     */
    public function isTypeWebsite()
    {
        return ($this->type == self::TYPE_WEBSITE);
    }
    
    /**
     * Check if type is store
     * 
     * @return boolean
     */
    public function isTypeStore()
    {
        return ($this->type == self::TYPE_STORE);
    }
    
    
    /**
     * Config wrapper
     * 
     * @param string $configPath
     * @return mixed
     */
    public function getConfig($configPath)
    {
        if ($this->isTypeGlobal()) {
            return Mage::getStoreConfig($configPath);
        }
        
        if ($this->isTypeStore()) {
            return Mage::getStoreConfig($configPath, $this->storeId);
        }
        
        if ($this->isTypeWebsite()) {
            return Mage::app()->getWebsite($this->websiteId)->getConfig($configPath);
        }
        
        return null;
    }
    
    
    public function getOrderCheckIsActive()
    {
        return (1 == $this->getConfig("rommonitoring/order_check/active"));
    }
    
    public function getOrderCheckRanges()
    {
        return unserialize($this->getConfig("rommonitoring/order_check/ranges"));
    }
    
    public function getStatisticDateFrom()
    {
        return $this->getConfig("rommonitoring/order_check/statistic_count_date_from");
    }
    
    public function getStatisticDateTo()
    {
        return $this->getConfig("rommonitoring/order_check/statistic_count_date_to");
    }
    
    public function getOrderCheckOrderStatus()
    {
        return explode(",", $this->getConfig("rommonitoring/order_check/order_status"));
    }
    
    public function getOrderCheckEmailSender()
    {
        return $this->getConfig("rommonitoring/order_check/sender_mail_identity");
    }
    
    public function getOrderCheckEmailReceiver()
    {
        return unserialize($this->getConfig("rommonitoring/order_check/email_receiver"));
    }
    public function getOrderCheckEmailTemplateMinimum()
    {
        return $this->getConfig("rommonitoring/order_check/email_template_min");
    }
    
    public function getOrderCheckEmailTemplateMaximum()
    {
        return $this->getConfig("rommonitoring/order_check/email_template_max");
    }
    
    public function getScopes()
    {
        return explode(",", $this->getConfig("rommonitoring/order_check/scopes"));
    }
}