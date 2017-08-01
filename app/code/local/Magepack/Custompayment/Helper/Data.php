<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 20.07.17
 * Time: 11:34
 */

class Magepack_Custompayment_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function customerBonusBalance($customer_id = null)
    {
        if (is_null($customer_id)) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
        }
        else {
            $customer = Mage::getModel('customer/customer')->load($customer_id);
        }
        return $customer->getData('bonus_balance');
    }
}