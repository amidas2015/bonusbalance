<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 20.07.17
 * Time: 12:40
 */

class Magepack_Custompayment_Model_Pay extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'custompayment';
    protected $_formBlockType = 'custompayment/form_custompayment';
//    protected $_infoBlockType = 'custompayment/info_custompayment';
    protected $_isInitializeNeeded      = false;//
    protected $_canUseInternal          = true;//false
    protected $_canUseForMultishipping  = false;

    protected $_isGateway = false;//
    protected $_canUseCheckout = true;
    protected $_canOrder = true;//
    protected $_canAuthorize = false;//
    protected $_canCapture = true;
    protected $_canVoid = false;//
//    protected $_canCapturePartial = false;
    protected $_canRefund = true;

    /**
     * Check whether payment method can be used
     *
     * TODO: payment method instance is not supposed to know about quote
     *
     * @param Mage_Sales_Model_Quote|null $quote
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        $customer_id = $quote->getCustomerId();
//        $bonus_balance = $this->customerBonusBalance($customer_id);
        $bonus_balance = Mage::helper('custompayment')->customerBonusBalance();
        $grand_total = $quote->getGrandTotal();

//        if(!(int)$bonus_balance)
        return ($bonus_balance < $grand_total) ? false : parent::isAvailable();
    }

    /**
     * Capture payment abstract method
     *
     * @param Varien_Object $payment
     * @param float $amounth
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function capture(Varien_Object $payment, $amount)
    {
        $customer_id = $payment->getOrder()->getQuote()->getCustomerId();
        $customer = Mage::getModel('customer/customer')->load($customer_id);
        $bonus_balance = $customer->getBonusBalance();

        if ($amount < 0) {
            Mage::throwException(Mage::helper('custompayment')->__('Invalid amount for capture.'));
        }
        if ($amount > $bonus_balance) {
            Mage::throwException(Mage::helper('custompayment')->__('Your bonus balance is less than the amount of the order.'));
        }

        $payment->setTransactionId($payment->getOrder()->getIncrement_id());
        $payment->setIsTransactionClosed(0);

        $customer->setBonusBalance($bonus_balance - $amount);
        $customer->save();

        return $this;
    }

    /**
     * Refund specified amount for payment
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function refund(Varien_Object $payment, $amount)
    {
        $customer_id = Mage::getModel('sales/order')->load($payment->getEntityId())
            ->getCustomerId();
        $customer = Mage::getModel('customer/customer')->load($customer_id);
        $bonus_balance = $customer->getBonusBalance();

        if ($amount < 0) {
            Mage::throwException(Mage::helper('custompayment')->__('Invalid amount for refund.'));
        }
        $customer->setBonusBalance($bonus_balance + $amount);
        $customer->save();

        return $this;
    }
}