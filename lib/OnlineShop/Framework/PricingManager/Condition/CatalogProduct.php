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


namespace OnlineShop\Framework\PricingManager\Condition;

class CatalogProduct extends AbstractObjectListCondition implements ICatalogProduct
{
    /**
     * @var \OnlineShop\Framework\Model\AbstractProduct[]
     */
    protected $products = [];

    /**
     * Serialized product IDs
     *
     * @var array
     */
    protected $productIds = [];

    /**
     * @param \OnlineShop\Framework\PricingManager\IEnvironment $environment
     *
     * @return boolean
     */
    public function check(\OnlineShop\Framework\PricingManager\IEnvironment $environment)
    {
        // init
        $productsPool = array();

        // get current product if we have one
        if($environment->getProduct())
        {
            $productsPool[] = $environment->getProduct();
        }

        // products from cart
        if($environment->getCart())
        {
            foreach($environment->getCart()->getItems() as $item)
            {
                $productsPool[] = $item->getProduct();
            }
        }


        // test
        foreach($productsPool as $currentProduct)
        {
            // check all valid products
            foreach($this->getProducts() as $product)
            {
                /* @var \OnlineShop\Framework\Model\AbstractProduct $allow */

                $currentProductCheck = $currentProduct;
                while($currentProductCheck instanceof \OnlineShop\Framework\Model\ICheckoutable) {
                    if($currentProductCheck->getId() === $product->getId())
                    {
                        return true;
                    }
                    $currentProductCheck = $currentProductCheck->getParent();
                }
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        // basic
        $json = array(
            'type' => 'CatalogProduct',
            'products' => array()
        );

        // add categories
        foreach($this->getProducts() as $product)
        {
            /* @var \OnlineShop\Framework\Model\AbstractProduct $product */
            $json['products'][] = array(
                $product->getId(),
                $product->getFullPath()
            );
        }

        return json_encode($json);
    }

    /**
     * @param string $string
     *
     * @return \OnlineShop\Framework\PricingManager\ICondition
     */
    public function fromJSON($string)
    {
        $json = json_decode($string);

        $products = array();
        foreach($json->products as $cat)
        {
            $product = $this->loadObject($cat->id);
            if($product)
            {
                $products[] = $product;
            }
        }
        $this->setProducts( $products );

        return $this;
    }

    /**
     * Don't cache the entire product object
     *
     * @return array
     */
    public function __sleep()
    {
        return $this->handleSleep('products', 'productIds');
    }

    /**
     * Restore products from serialized ID list
     */
    public function __wakeup()
    {
        $this->handleWakeup('products', 'productIds');
    }

    /**
     * @param \OnlineShop\Framework\Model\AbstractProduct[] $products
     *
     * @return ICatalogProduct
     */
    public function setProducts(array $products)
    {
        $this->products = $products;
        return $this;
    }

    /**
     * @return \OnlineShop\Framework\Model\AbstractProduct[]
     */
    public function getProducts()
    {
        return $this->products;
    }
}
