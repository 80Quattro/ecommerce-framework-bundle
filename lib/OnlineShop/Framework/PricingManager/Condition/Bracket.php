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

class Bracket implements IBracket
{
    /**
     * @var array|\OnlineShop\Framework\PricingManager\ICondition
     */
    protected $conditions = array();

    /**
     * @var array|IBracket::OPERATOR_*
     */
    protected $operator = array();

    /**
     * @param \OnlineShop\Framework\PricingManager\ICondition $condition
     * @param string $operator IBracket::OPERATOR_*
     *
     * @return IBracket
     */
    public function addCondition(\OnlineShop\Framework\PricingManager\ICondition $condition, $operator)
    {
        $this->conditions[] = $condition;
        $this->operator[] = $operator;
        return $this;
    }

    /**
     * @param \OnlineShop\Framework\PricingManager\IEnvironment $environment
     *
     * @return boolean
     */
    public function check(\OnlineShop\Framework\PricingManager\IEnvironment $environment)
    {
        // A bracket without conditions is not restricted and thus doesn't fail
        if (sizeof($this->conditions) == 0) return true;
        
        // default
        $state = false;

        // check all conditions
        foreach($this->conditions as $num => $condition)
        {
            /* @var \OnlineShop\Framework\PricingManager\ICondition $condition */

            // test condition
            $check = $condition->check($environment);

            // check
            switch($this->operator[$num])
            {
                // first condition
                case null:
                    $state = $check;
                    break;

                // AND
                case IBracket::OPERATOR_AND:
                    if($check === false)
                        return false;
                    else
                        $state = true;
                    break;

                // AND FALSE
                case IBracket::OPERATOR_AND_NOT:
                    if($check === true)
                        return false;
                    else
                        $state = true;
                    break;

                // OR
                case IBracket::OPERATOR_OR:
                    if($check === true)
                        $state = $check;
                    break;
            }
        }

        return $state;
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        $json = array('type' => 'Bracket', 'conditions' => array());
        foreach($this->conditions as $num => $condition)
        {
            if($condition) {
                /* @var \OnlineShop\Framework\PricingManager\ICondition $condition */
                $cond = array(
                    'operator' => $this->operator[$num],
                    'condition' => json_decode($condition->toJSON())
                );
                $json['conditions'][] = $cond;
            }

        }

        return json_encode($json);
    }

    /**
     * @param string $string
     *
     * @return $this|\OnlineShop\Framework\PricingManager\ICondition
     */
    public function fromJSON($string)
    {
        $json = json_decode($string);

        foreach($json->conditions as $setting)
        {
            $subcond = \OnlineShop\Framework\Factory::getInstance()->getPricingManager()->getCondition( $setting->type );
            $subcond->fromJSON( json_encode($setting) );

            $this->addCondition($subcond, $setting->operator);
        }

        return $this;
    }
}
