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


namespace OnlineShop\Framework\OrderManager;

use \OnlineShop\Framework\Model\AbstractOrder as Order;
use \OnlineShop\Framework\Model\AbstractOrderItem as OrderItem;

interface IOrderListItem
{
    /**
     * @return int
     */
    public function getId();


    /**
     * @return Order|OrderItem
     */
    public function reference();
}