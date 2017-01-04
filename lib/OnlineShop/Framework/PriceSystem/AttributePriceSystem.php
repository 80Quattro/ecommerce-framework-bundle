<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @category   Pimcore
 * @package    EcommerceFramework
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */


namespace OnlineShop\Framework\PriceSystem;
use OnlineShop\Framework\PriceSystem\TaxManagement\TaxCalculationService;
use OnlineShop\Framework\PriceSystem\TaxManagement\TaxEntry;

/**
 * Class AttributePriceSystem
 *
 * price system implementation for attribute price system
 */
class AttributePriceSystem extends CachingPriceSystem implements IPriceSystem {

    /**
     * @param $productIds
     * @param $fromPrice
     * @param $toPrice
     * @param $order
     * @param $offset
     * @param $limit
     * @throws \Exception
     */
    public function filterProductIds($productIds, $fromPrice, $toPrice, $order, $offset, $limit) {
        throw new \Exception("not supported yet");
    }

    /**
     * @param $quantityScale
     * @param $product
     * @param $products
     *
     * @internal param $infoConstructorParams
     * @return AbstractPriceInfo
     */
    function createPriceInfoInstance($quantityScale, $product, $products) {

        $taxClass = $this->getTaxClassForProduct($product);

        $amount = $this->calculateAmount($product, $products);
        $price = $this->getPriceClassInstance($amount);
        $totalPrice = $this->getPriceClassInstance($amount * $quantityScale);


        if($taxClass) {
            $price->setTaxEntryCombinationMode($taxClass->getTaxEntryCombinationType());
            $price->setTaxEntries(TaxEntry::convertTaxEntries($taxClass));

            $totalPrice->setTaxEntryCombinationMode($taxClass->getTaxEntryCombinationType());
            $totalPrice->setTaxEntries(TaxEntry::convertTaxEntries($taxClass));
        }

        $taxCalculationService =  $this->getTaxCalculationService();
        $taxCalculationService->updateTaxes($price, TaxCalculationService::CALCULATION_FROM_GROSS);
        $taxCalculationService->updateTaxes($totalPrice, TaxCalculationService::CALCULATION_FROM_GROSS);

        return new AttributePriceInfo($price, $quantityScale, $totalPrice);
    }

    /**
     * calculates prices from product
     *
     * @param $product
     * @param $products
     * @return float
     * @throws \Exception
     */
    protected function calculateAmount($product, $products) {
        $getter = "get" . ucfirst($this->config->attributename);
        if(method_exists($product, $getter)) {

            if(!empty($products)) {
                $sum = 0;
                foreach($products as $p) {

                    if($p instanceof \OnlineShop\Framework\Model\AbstractSetProductEntry) {
                        $sum += $p->getProduct()->$getter() * $p->getQuantity();
                    } else {
                        $sum += $p->$getter();
                    }
                }
                return $sum;

            } else {
                return $product->$getter();
            }
        }
    }

    /**
     * returns default currency based on environment settings
     *
     * @return \Zend_Currency
     */
    protected function getDefaultCurrency() {
        return new \Zend_Currency(\OnlineShop\Framework\Factory::getInstance()->getEnvironment()->getCurrencyLocale());
    }

    /**
     * creates instance of \OnlineShop\Framework\PriceSystem\IPrice
     *
     * @param $amount
     * @return \OnlineShop\Framework\PriceSystem\IPrice
     * @throws \Exception
     */
    protected function getPriceClassInstance($amount) {
        if($this->config->priceClass) {
            $price = new $this->config->priceClass($amount, $this->getDefaultCurrency(), false);
            if(!$price instanceof \OnlineShop\Framework\PriceSystem\IPrice) {
                throw new \Exception('Price Class does not implement \OnlineShop\Framework\PriceSystem\IPrice');
            }
        } else {
            $price = new Price($amount, $this->getDefaultCurrency(), false);
        }
        return $price;
    }
}
