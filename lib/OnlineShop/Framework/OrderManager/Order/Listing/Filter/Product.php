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


namespace OnlineShop\Framework\OrderManager\Order\Listing\Filter;

use OnlineShop\Framework\Impl\OrderManager\Order\Listing\Filter\AbstractItem;
use OnlineShop\Framework\OrderManager\IOrderList;
use OnlineShop\Framework\OrderManager\IOrderListFilter;

class Product implements IOrderListFilter
{
    /**
     * @var \Pimcore\Model\Object\Concrete
     */
    protected $product;

    /**
     * @param \Pimcore\Model\Object\Concrete $product
     */
    public function __construct(\Pimcore\Model\Object\Concrete $product)
    {
        $this->product = $product;
    }

    /**
     * @param IOrderList $orderList
     * @return IOrderListFilter
     */
    public function apply(IOrderList $orderList)
    {
        $ids = [
            $this->product->getId()
        ];

        $variants = $this->product->getChilds([
            \Pimcore\Model\Object\Concrete::OBJECT_TYPE_VARIANT
        ]);

        /** @var \Pimcore\Model\Object\Concrete $variant */
        foreach ($variants as $variant) {
            $ids[] = $variant->getId();
        }

        $orderList->addCondition('orderItem.product__id IN (?)', $ids);

        return $this;
    }
}