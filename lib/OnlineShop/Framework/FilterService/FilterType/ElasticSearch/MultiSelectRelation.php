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


namespace OnlineShop\Framework\FilterService\FilterType\ElasticSearch;

class MultiSelectRelation extends \OnlineShop\Framework\FilterService\FilterType\MultiSelectRelation {

    public function prepareGroupByValues(\OnlineShop\Framework\Model\AbstractFilterDefinitionType $filterDefinition, \OnlineShop\Framework\IndexService\ProductList\IProductList $productList) {
        $field = $this->getField($filterDefinition);
        $productList->prepareGroupByRelationValues($field, true, !$filterDefinition->getUseAndCondition());
    }

    public function addCondition(\OnlineShop\Framework\Model\AbstractFilterDefinitionType $filterDefinition, \OnlineShop\Framework\IndexService\ProductList\IProductList $productList, $currentFilter, $params, $isPrecondition = false) {
        $field = $this->getField($filterDefinition);
        $preSelect = $this->getPreSelect($filterDefinition);


        $value = $params[$field];

        if(empty($value) && !$params['is_reload']) {
            $objects = $preSelect;
            $value = array();

            if(!is_array($objects)) {
                $objects = explode(",", $objects);
            }

            if (is_array($objects)){
                foreach($objects as $o) {
                    if(is_object($o)) {
                        $value[] = $o->getId();
                    } else {
                        $value[] = $o;
                    }
                }
            }

        } else if(!empty($value) && in_array(\OnlineShop\Framework\FilterService\FilterType\AbstractFilterType::EMPTY_STRING, $value)) {
            $value = null;
        }

        $currentFilter[$field] = $value;

        if(!empty($value)) {
            $quotedValues = array();
            foreach($value as $v) {
                if(!empty($v)) {
                    $quotedValues[] = $v;
                }
            }
            if(!empty($quotedValues)) {
                if($filterDefinition->getUseAndCondition()) {
                    foreach ($quotedValues as $value) {
                        $productList->addRelationCondition($field, $value);
                    }
                } else {
                    $productList->addRelationCondition($field, ['terms' => [$field => $quotedValues]]);
                }
            }
        }
        return $currentFilter;
    }
}
