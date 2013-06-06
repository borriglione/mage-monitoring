<?php
class Rom_Monitoring_Block_Adminhtml_System_Config_Form_Field_Ranges extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('from_time', array(
            'label' => Mage::helper('adminhtml')->__('From Time'),
            'style' => 'width:100px',
            'class' => 'required-entry'
        ));
        $this->addColumn('to_time', array(
            'label' => Mage::helper('adminhtml')->__('To Time'),
            'style' => 'width:100px',
            'class' => 'required-entry'
        ));
        $this->addColumn('order_count', array(
            'label' => Mage::helper('adminhtml')->__('Order count'),
            'style' => 'width:100px',
            'class' => 'required-entry validate-number'
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add range');
        parent::__construct();
    }
}
