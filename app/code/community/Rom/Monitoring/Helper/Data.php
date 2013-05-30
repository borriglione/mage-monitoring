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
}