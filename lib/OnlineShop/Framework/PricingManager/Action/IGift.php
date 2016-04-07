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


namespace OnlineShop\Framework\PricingManager\Action;

/**
 * add a gift product to the given cart
 */
interface IGift extends \OnlineShop\Framework\PricingManager\IAction
{
    /**
     * set gift product
     * @param \OnlineShop\Framework\Model\AbstractProduct $product
     *
     * @return IGift
     */
    public function setProduct(\OnlineShop\Framework\Model\AbstractProduct $product);

    /**
     * @return \OnlineShop\Framework\Model\AbstractProduct
     */
    public function getProduct();
}