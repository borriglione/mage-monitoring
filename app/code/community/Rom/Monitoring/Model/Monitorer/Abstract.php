<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Model_Monitorer_Abstract extends Varien_Object
{
    /**
     * @var Rom_Monitoring_Model_Config
     */
    protected $config = null;
    
    
    /**
     * var Rom_Monitoring_Model_Log
     */
    protected $logHandler = null;
    
    /**
     * Log handler
     * 
     * @return Rom_Monitoring_Model_Log
     */
    protected function getLogHandler()
    {
        if (true === is_null($this->logHandler)) {
            $this->logHandler = Mage::getModel("rommonitoring/log");
        } 
        return $this->logHandler;
    }
    
    /**
     * Send emails for failed checks
     * 
     * @param string $senderKey "sales"
     * @param array $receiverEmails
     * @param string $eMailTemplate "rommonitoring_email_template_order_check"
     * @param array $eMailTemplateData
     * @return void
     * @throws Mage_Core_Exception
     */
    public function sendCheckFailedMail($senderKey, $receiverEmails, $eMailTemplate, $eMailTemplateData)
    {
        $mailTemplate = Mage::getModel('core/email_template');
        
        /* @var $mailTemplate Mage_Core_Model_Email_Template */
        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
            ->setReplyTo($senderKey)
            ->sendTransactional(
                $eMailTemplate,
                $senderKey,
                $receiverEmails,
                null,
                $eMailTemplateData
            );

        if (false === $mailTemplate->getSentSuccess()) {
            Mage::throwException(
                Mage::helper("rommonitoring/data")->__("Failed Check Mail could not be sent! Log: %s", var_export($eMailTemplateData, true))
            );
        }
    }

    /**
     * Get Monitoring config model
     * 
     * @return Rom_Monitoring_Model_Config
     */
    public function getConfig()
    {
        if (true === is_null($this->config)) {
            $this->config = Mage::getModel("rommonitoring/config");
        }
        return $this->config;
    }

    /**
     *Set Monitoring config model
     * 
     * @param Rom_Monitoring_Model_Config $config
     * @return Rom_Monitoring_Model_Monitorer_OrderCheck
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }
    
    /**
     * Get store ids to filter
     * 
     * @return array
     */
    protected function getStoreIdFilter()
    {
        if ($this->config->isTypeGlobal()) {
            return array();
        }
        
        if ($this->config->isTypeStore()) {
            return array($this->config->storeId);
        }
        
        if ($this->config->isTypeWebsite()) {
            return Mage::app()->getWebsite($this->config->websiteId)->getStoreIds();
        }
    }
}