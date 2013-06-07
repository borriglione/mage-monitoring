<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Block_Adminhtml_System_Config_Info extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template = 'rommonitoring/system/config/info.phtml';
    
    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $fieldset
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $fieldset)
    {
        $originalData = $fieldset->getOriginalData();
        $this->addData(array(
            'fieldset_label' => $fieldset->getLegend(),
        ));
        return $this->toHtml();
    }
}
