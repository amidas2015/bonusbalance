<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.07.2017
 * Time: 18:59
 */

class Magepack_Custompayment_Model_Observer
{
    public function customerSaveBefore($observer)
    {
          $customer = $observer->getCustomer();
          $default_bonus_balance = Mage::getStoreConfig('custompayment_section/custompayment_group/default_bonus_balance', Mage::app()->getStore());
          if ($default_bonus_balance && $customer->isObjectNew()) {
              $customer->setBonusBalance($default_bonus_balance);
          }
    }
}