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
    public function getOrderCheckRanges()
    {
        return unserialize(Mage::getStoreConfig("rommonitoring/order_check/ranges"));
    }
    
    public function getOrderCheckOrderStatus()
    {
        return explode(",", Mage::getStoreConfig("rommonitoring/order_check/order_status"));
    }
    
    public function getOrderCheckEmailSender()
    {
        return Mage::getStoreConfig("rommonitoring/order_check/sender_mail_identity");
    }
    
    public function getOrderCheckEmailReceiver()
    {
        return unserialize(Mage::getStoreConfig("rommonitoring/order_check/email_receiver"));
    }
    public function getOrderCheckEmailTemplate()
    {
        return Mage::getStoreConfig("rommonitoring/order_check/email_template");
    }
}