<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Block_Adminhtml_Config_Statistic extends Mage_Adminhtml_Block_Abstract
{
    /**
     * Get minimum statistic for configured time ranges
     * 
     * @return array
     */
    public function getMinRangeStatistic()
    {
        return
            Mage::getModel("rommonitoring/config_statistic")
                ->getMinimumStatistic();
    }
    
    /**
     * Get maximum statistic for configured time ranges
     * 
     * @return array
     */
    public function getMaxRangeStatistic()
    {
        return
            Mage::getModel("rommonitoring/config_statistic")
                ->getMaximumStatistic();
    }
}
