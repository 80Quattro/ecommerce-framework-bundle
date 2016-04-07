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


namespace OnlineShop\Framework\FilterService\FilterType;

class NumberRangeSelection extends AbstractFilterType {

    public function getFilterFrontend(\OnlineShop\Framework\Model\AbstractFilterDefinitionType $filterDefinition, \OnlineShop\Framework\IndexService\ProductList\IProductList $productList, $currentFilter) {

        $field = $this->getField($filterDefinition);
        if ($filterDefinition->getScriptPath()) {
            $script = $filterDefinition->getScriptPath();
        } else {
            $script = $this->script;
        }

        $ranges = $filterDefinition->getRanges();

        $groupByValues = $productList->getGroupByValues($field, true);

        $counts = array();
        foreach($ranges->getData() as $row) {
            $counts[$row['from'] . "_" . $row['to']] = 0;
        }

        foreach($groupByValues as $groupByValue) {
            if($groupByValue['value'] !== null) {
                $value = floatval($groupByValue['value']);

                if(!$value) {
                    $value = 0;
                }
                foreach($ranges->getData() as $row) {
                    if((empty($row['from']) || (floatval($row['from']) <= $value)) && (empty($row['to']) || floatval($row['to']) > $value)) {
                        $counts[$row['from'] . "_" . $row['to']] += $groupByValue['count'];
                        break;
                    }
                }
            }
        }
        $values = array();
        foreach($ranges->getData() as $row) {
            if($counts[$row['from'] . "_" . $row['to']]) {
                $values[] = array("from" => $row['from'], "to" => $row['to'], "label" => $this->createLabel($row), "count" => $counts[$row['from'] . "_" . $row['to']], "unit" => $filterDefinition->getUnit());
            }
        }

        $currentValue = "";
        if($currentFilter[$field]['from'] || $currentFilter[$field]['to']) {
            $currentValue = implode($currentFilter[$field], "-");
        }


        return $this->view->partial($script, array(
            "hideFilter" => $filterDefinition->getRequiredFilterField() && empty($currentFilter[$filterDefinition->getRequiredFilterField()]),
            "label" => $filterDefinition->getLabel(),
            "currentValue" => $currentValue,
            "currentNiceValue" => $this->createLabel($currentFilter[$field]),
            "unit" => $filterDefinition->getUnit(),
            "values" => $values,
            "definition" => $filterDefinition,
            "fieldname" => $field,
            "metaData" => $filterDefinition->getMetaData()
        ));
    }

    private function createLabel($data) {
        if(is_array($data)) {
            if(!empty($data['from'])) {
                if(!empty($data['to'])) {
                    return $data['from'] . " - " . $data['to'];
                } else {
                    return $this->view->translate("more than") . " " . $data['from'];
                }
            } else if(!empty($data['to'])) {
                return $this->view->translate("less than") . " " . $data['to'];
            }
        } else {
            return "";
        }
    }

    public function addCondition(\OnlineShop\Framework\Model\AbstractFilterDefinitionType $filterDefinition, \OnlineShop\Framework\IndexService\ProductList\IProductList $productList, $currentFilter, $params, $isPrecondition = false) {
        $field = $this->getField($filterDefinition);
        $rawValue = $params[$field];

        if(!empty($rawValue) && $rawValue != AbstractFilterType::EMPTY_STRING && is_string($rawValue)) {
            $values = explode("-", $rawValue);
            $value['from'] = trim($values[0]);
            $value['to'] = trim($values[1]);
        } else if($rawValue == AbstractFilterType::EMPTY_STRING) {
            $value = null;
        } else {
            $value['from'] = $filterDefinition->getPreSelectFrom();
            $value['to'] = $filterDefinition->getPreSelectTo();
        }

        $currentFilter[$field] = $value;


        if(!empty($value)) {
            if(!empty($value['from'])) {

                if($isPrecondition) {
                    $productList->addCondition($field . " >= " . $productList->quote($value['from']), "PRECONDITION_" . $field);
                } else {
                    $productList->addCondition($field . " >= " . $productList->quote($value['from']), $field);
                }

            }
            if(!empty($value['to'])) {

                if($isPrecondition) {
                    $productList->addCondition($field . " <= " . $productList->quote($value['to']), "PRECONDITION_" . $field);
                } else {
                    $productList->addCondition($field . " < " . $productList->quote($value['to']), $field);
                }

            }
        }
        return $currentFilter;
    }
}
