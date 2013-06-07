<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Block_Adminhtml_System_Config_Form_Field_Ranges extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    /**
    * Count Type renderer
    *
    * @var Rom_Monitoring_Block_Html_Select
    */
    protected $countTypeRenderer = null;
    
    /**
     * Contructor
     */
    public function __construct()
    {
        $this->addColumn('from_time', array(
            'label' => Mage::helper('rommonitoring')->__('From Time'),
            'style' => 'width:80px',
            'class' => 'required-entry'
        ));
        $this->addColumn('to_time', array(
            'label' => Mage::helper('rommonitoring')->__('To Time'),
            'style' => 'width:80px',
            'class' => 'required-entry'
        ));
        
        $this->addColumn('count_type', array(
            'label'  => Mage::helper('rommonitoring')->__('Type')
        ));
        
        $this->addColumn('order_count', array(
            'label' => Mage::helper('rommonitoring')->__('Order count'),
            'style' => 'width:80px',
            'class' => 'required-entry validate-number'
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add range');
        parent::__construct();
    }

    /**
     * Get select block for count type
     *
     * @return Rom_Monitoring_Block_Html_Select
     */
    protected function getCountTypeRenderer()
    {
      if (true === is_null($this->countTypeRenderer)) {
          $this->countTypeRenderer = $this->getLayout()
                  ->createBlock('rommonitoring/html_select')
                  ->setIsRenderToJsTemplate(true);
      }
      return $this->countTypeRenderer;
    }
    
    /**
     *
     * My renderer does need parameters that are not supported by original implementation
     *
     * @param string $columnName
     * @return Rom_Monitoring_Block_Adminhtml_System_Config_Form_Field_Ranges
     */
    protected function _renderCellTemplate($columnName)
    {
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
     
        if ($columnName == "count_type") {
            return $this->getCountTypeRenderer()
                    ->setName($inputName)
                    ->setTitle($columnName)
                    ->setExtraParams('style="width:80px"')
                    ->setClass('required-entry')
                    ->setOptions(
                        Mage::getModel("rommonitoring/system_config_source_countType")
                            ->toOptionArray(null))
                    ->toHtml();
        }
        return parent::_renderCellTemplate($columnName);
    }
}
