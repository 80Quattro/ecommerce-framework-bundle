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


namespace OnlineShop\Framework\PricingManager\Rule\Listing;

class Dao extends \Pimcore\Model\Listing\Dao\AbstractDao
{
    /**
     * @var string
     */
    protected $ruleClass = '\OnlineShop\Framework\PricingManager\Rule';

    /**
     * @return array
     */
    public function load() {
        $rules = array();

        // load objects
        $ruleIds = $this->db->fetchCol("SELECT id FROM " . \OnlineShop\Framework\PricingManager\Rule\Dao::TABLE_NAME .
                                                 $this->getCondition() . $this->getOrder() . $this->getOffsetLimit());

        foreach ($ruleIds as $id)
            $rules[] = call_user_func(array($this->getRuleClass(), 'getById'), $id);

        $this->model->setRules($rules);

        return $rules;
    }

    public function setRuleClass($cartClass)
    {
        $this->ruleClass = $cartClass;
    }

    public function getRuleClass()
    {
        return $this->ruleClass;
    }

}