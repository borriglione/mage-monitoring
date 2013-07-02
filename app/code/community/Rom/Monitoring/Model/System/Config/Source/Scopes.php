<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Model_System_Config_Source_Scopes
{
    /**
     * @var string
     */
    const KEY_WEBSITE = "website_";
    
    /**
     * @var string
     */
    const KEY_STORE = "store_";
    
    /**
     * @var string
     */
    const KEY_GLOBAL = "#global#";
    
    /**
     * Build Website/Store array for configuration area
     * 
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        
        //Global option
        $options[] = array(
            'value' => self::KEY_GLOBAL,
            'label' => Mage::helper('rommonitoring')->__('Global')
        );
        
        //For every website
        foreach (Mage::app()->getWebsites() as $website) {
            //Website option
            $options[] = array(
                'value' => self::KEY_WEBSITE.$website->getId(),
                'label' => $website->getName()
            );
            
            //Website optgroup option
            $options[self::KEY_WEBSITE.$website->getId()] = array(
                'value' => array(),
                'label' => $website->getName()
            );
            
            //For every store
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    //Store option
                    $options[self::KEY_WEBSITE.$website->getId()]['value'][] = array(
                        'value' => self::KEY_STORE.$store->getId(),
                        'label' => $store->getName()
                    );
                }
            }
        }
        
        return $options;
    }
}