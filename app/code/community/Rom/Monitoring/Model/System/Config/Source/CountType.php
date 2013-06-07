<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Model_System_Config_Source_CountType
{
    const COUNT_TYPE_MIN = "min";
    const COUNT_TYPE_MAX = "max";
    
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
            'value' => self::COUNT_TYPE_MIN,
            'label' => Mage::helper('rommonitoring')->__('Minimum')
        );
        $options[] = array(
            'value' => self::COUNT_TYPE_MAX,
            'label' => Mage::helper('rommonitoring')->__('Maximum')
        );
        
        return $options;
    }
}