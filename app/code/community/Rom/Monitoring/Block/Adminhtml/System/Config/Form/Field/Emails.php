<?php
class Rom_Monitoring_Block_Adminhtml_System_Config_Form_Field_Emails extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('email', array(
            'label' => Mage::helper('adminhtml')->__('Email'),
            'style' => 'width:200px',
            'class' => 'required-entry validate-email'
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Email');
        parent::__construct();
    }
}
