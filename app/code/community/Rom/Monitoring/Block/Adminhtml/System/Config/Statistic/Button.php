<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Block_Adminhtml_System_Config_Statistic_Button 
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    /**
     * add button block to the rendered html
     * 
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        
        $urlParameters = array();
        if (false === is_null(Mage::app()->getRequest()->getParam("website"))) {
            $urlParameters["website_key"] = Mage::app()->getRequest()->getParam("website");
        }
        if (false === is_null(Mage::app()->getRequest()->getParam("store"))) {
            $urlParameters["store_key"] = Mage::app()->getRequest()->getParam("store");
        }
        
        $url = Mage::helper('adminhtml')->getUrl(
            'rommonitoring/adminhtml_config/statistic',
            $urlParameters
        ); 
        $url = rtrim($url,'/');
        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('scalable')
            ->setLabel('Create statistic')
            ->setOnClick("window.open('{$url}')")
            ->toHtml();

        return $html;
    }
}
?>
