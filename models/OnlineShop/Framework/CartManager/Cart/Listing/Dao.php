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


namespace OnlineShop\Framework\CartManager\Cart\Listing;

class Dao extends \Pimcore\Model\Listing\Dao\AbstractDao {

    protected $cartClass = '\OnlineShop\Framework\CartManager\Cart';

    /**
     * @return array
     */
    public function load() {
        $carts = array();
        $cartIds = $this->db->fetchCol("SELECT id FROM " . \OnlineShop\Framework\CartManager\Cart\Dao::TABLE_NAME .
                                                 $this->getCondition() . $this->getOrder() . $this->getOffsetLimit());

        foreach ($cartIds as $id) {
            $carts[] = call_user_func(array($this->getCartClass(), 'getById'), $id);
        }

        $this->model->setCarts($carts);

        return $carts;
    }

    public function getTotalCount() {
        $amount = $this->db->fetchRow("SELECT COUNT(*) as amount FROM `" . \OnlineShop\Framework\CartManager\Cart\Dao::TABLE_NAME . "`" . $this->getCondition());
        return $amount["amount"];
    }

    public function setCartClass($cartClass)
    {
        $this->cartClass = $cartClass;
    }

    public function getCartClass()
    {
        return $this->cartClass;
    }

}