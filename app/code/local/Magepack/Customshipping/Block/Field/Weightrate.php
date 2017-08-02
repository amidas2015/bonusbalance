<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 31.07.17
 * Time: 16:10
 */

class Magepack_Customshipping_Block_Field_Weightrate extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('threshold_weight', array(
            'label' => Mage::helper('customshipping')->__('Weight'),
            'style' => 'width:120px',
            'type'  => 'number',
            'class' => 'validate-number'
        ));
        $this->addColumn('threshold_rate', array(
            'label' => Mage::helper('customshipping')->__('Price'),
            'style' => 'width:120px',
            'type'  => 'price',
            'class' => 'validate-number'
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('customshipping')->__('Add Threshold');
        parent::__construct();
    }
}