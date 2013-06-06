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
    public function check()
    {
        if (true === Mage::getModel("rommonitoring/config")->getOrderCheckIsActive()) {
            Mage::getModel("rommonitoring/monitorer_orderCheck")->check();
        }
    }
}