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


namespace OnlineShop\Framework\OrderManager\Order\Listing;

use OnlineShop\Framework\OrderManager;
use OnlineShop\Framework\OrderManager\IOrderListItem;
use OnlineShop\Framework\OrderManager\AbstractOrderListItem;
use \OnlineShop\Framework\Model\AbstractOrder as Order;
use \OnlineShop\Framework\Model\AbstractOrderItem as OrderItem;

class Item extends AbstractOrderListItem implements IOrderListItem
{
    /**
     * @return int
     */
    public function getId()
    {
        return $this->resultRow['Id'];
    }


    /**
     * @param $method
     * @param $args
     *
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        $field = substr($method, 3);
        if(substr($method, 0, 3) == 'get' && array_key_exists($field, $this->resultRow))
        {
            return $this->resultRow[ $field ];
        }

        $object = $this->reference();
        if($object)
        {
            return call_user_func_array(array($object, $method), $args);
        }
        else
        {
            throw new \Exception("Object with {$this->getId()} not found.");
        }
    }

    /**
     * @return Order|OrderItem
     */
    public function reference()
    {
        return \Pimcore\Model\Object\Concrete::getById( $this->getId() );
    }
}