<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 24.07.17
 * Time: 13:01
 */

class Magepack_Custompayment_Block_Form_Custompayment extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('custompayment/form/custompayment.phtml');
    }

    protected function _customerBonusBalance()
    {
        return Mage::helper('custompayment')->customerBonusBalance();
    }
}