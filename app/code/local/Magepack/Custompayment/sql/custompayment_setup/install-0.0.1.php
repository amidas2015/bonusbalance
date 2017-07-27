<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 20.07.17
 * Time: 16:32
 */

$installer = $this;
$attribute  = array(
    'type' => 'decimal',
    'input' => 'text',
    'label' => 'Bonus balance',
    'global' => 1,
    'visible' => 1,
    'required' => false,
    'user_defined' => 0,
    'visible_on_front' => false,
    'comment' => 'Bonus customer balance',
);

$installer->addAttribute('customer', 'bonus_balance', $attribute);

Mage::getSingleton('eav/config')
    ->getAttribute('customer', 'bonus_balance')
    ->setData('used_in_forms', array('adminhtml_customer'))
    ->save();

$installer->endSetup();
