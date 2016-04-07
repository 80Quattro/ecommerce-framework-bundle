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

/**
 * Interface for PriceInfo implementations of online shop framework
 */
interface IPriceInfo {
    const MIN_PRICE = "min";

    /**
     * returns single price
     *
     * @abstract
     * @return IPrice
     */
    public function getPrice();

    /**
     * returns total price (single price * quantity)
     *
     * @abstract
     * @return IPrice
     */
    public function getTotalPrice();

    /**
     * returns if price is a minimal price (e.g. when having many product variants they might have a from price)
     *
     * @abstract
     * @return bool
     */
    public function isMinPrice();

    /**
     * returns quantity
     *
     * @abstract
     * @return int
     */
    public function getQuantity();

    /**
     * @param int|string $quantity
     * numeric quantity or constant IPriceInfo::MIN_PRICE
     */
    public function setQuantity($quantity);

    /**
     * relation to price system
     *
     * @abstract
     * @param \OnlineShop\Framework\PriceSystem\IPriceSystem $priceSystem
     * @return IPriceInfo
     */
    public function setPriceSystem($priceSystem);

    /**
     * relation to product
     *
     * @param \OnlineShop\Framework\Model\ICheckoutable $product
     *
     * @return IPriceInfo
     */
    public function setProduct(\OnlineShop\Framework\Model\ICheckoutable $product);

    /**
     * returns product
     *
     * @return \OnlineShop\Framework\Model\ICheckoutable
     */
    public function getProduct();
}