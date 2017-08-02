<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 31.07.17
 * Time: 14:50
 */

/**
 * Custom shipping implementation
 *
 */

class Magepack_Customshipping_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface {

    protected $_code = 'customshipping';

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return array(
            'customshipping'    =>  $this->getConfigData('name'),
        );
    }


    /**
     * Collect and get rates
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return  Mage_Shipping_Model_Rate_Result|bool|false|
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = Mage::getModel('shipping/rate_result');

        $currentWeight = $request->getPackageWeight();
        $result->append($this->_getDefaultRate($currentWeight));

        return $result;
    }


    /**
     * Get rates
     *
     * @param numeric $currentWeight
     * @return Mage_Shipping_Model_Rate_Result_Method|Mage_Shipping_Model_Rate_Result_Error
     */
    protected function _getDefaultRate($currentWeight)
    {
        $rate = Mage::getModel('shipping/rate_result_method');

        $arrayThreshold = @unserialize(Mage::getStoreConfig('carriers/customshipping/threshold', Mage::app()->getStore()));
        $maxThresholdWeight = 0;
        $maxThresholdRate = $this->getConfigData('default_price') ? : 0;

        if (!empty($arrayThreshold)) {
            foreach ($arrayThreshold as $threshold) {
                $currentThresholdWeight = $threshold['threshold_weight'];
                $currentThresholdRate = $threshold['threshold_rate'];
                if (is_numeric($currentThresholdWeight) && is_numeric($currentThresholdRate)) {
                    if (!empty($currentThresholdWeight) && $currentWeight >= $currentThresholdWeight) {
                        if ($maxThresholdWeight <= $currentThresholdWeight) {
                            $maxThresholdWeight = $currentThresholdWeight;
                            $maxThresholdRate = $currentThresholdRate;
                        }
                    }
                } else {
                    $error = $this->_getModel('shipping/rate_result_error');
                    $error->setCarrier('customshipping');
                    $error->setCarrierTitle($this->getConfigData('title'));
                    $error->setErrorMessage('Error');
                    return $error;
                }
            }
        }
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod($this->_code);
        $rate->setMethodTitle($this->getConfigData('name'));
        $rate->setPrice($maxThresholdRate); //The cost is what the merchant pays
        $rate->setCost(0); //The price is what the customer pays

        return $rate;
    }

    /**
     * Check if carrier has shipping tracking option available
     *
     * @return boolean
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * Get Model
     *
     * @param string $modelName
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _getModel($modelName)
    {
        return Mage::getModel($modelName);
    }
}