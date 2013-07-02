<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Model_Observer
{
    /**
     * @var Rom_Monitoring_Model_Config
     */
    protected $config = null;
    
    /**
     * Basic monitoring check function
     * 
     * @return void
     */
    public function check()
    {
        //If monitoring extension is activated
        if (true === $this->getConfig()->getOrderCheckIsActive()) {
            //For every scope
            foreach ($this->getConfig()->getScopes() as $scope) {
                //Set scoped config model
                $this->config = Mage::helper("rommonitoring/data")
                    ->defineScope($this->config, $scope);
                
                //In case the config model is null
                if (true === is_null($this->config)) {
                    continue;
                }
                
                //Run check with individual scope
                Mage::getModel("rommonitoring/monitorer_orderCheck")
                    ->setConfig($this->config)
                    ->check();
            }
        }
    }
    
    /**
     * Get Monitoring config model
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
}