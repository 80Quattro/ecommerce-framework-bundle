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


namespace OnlineShop\Framework\Model;

/**
 * Abstract base class for filter definition type field collections for category filter
 */
abstract class CategoryFilterDefinitionType extends \OnlineShop\Framework\Model\AbstractFilterDefinitionType {

    /**
     * @return string
     */
    public function getField() {
        if($this->getIncludeParentCategories()) {
            return "parentCategoryIds";
        } else {
            return "categoryIds";
        }
    }

    /**
     * @return bool
     */
    public function getIncludeParentCategories() {
        return false;
    }

}