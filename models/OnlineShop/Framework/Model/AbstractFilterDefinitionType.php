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
 * Abstract base class for filter definition type field collections
 */
abstract class AbstractFilterDefinitionType extends \Pimcore\Model\Object\Fieldcollection\Data\AbstractData {

    protected $metaData = [];

    /**
     * @return array
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * @param array $metaData
     *
     * @return $this
     */
    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
        return $this;
    }


    /**
    * @return string
    */
    public abstract function getLabel();

    /**
    * @return string
    */
    public abstract function getField();

    /**
     * @return string
     */
    public abstract function getScriptPath();

    /**
     * @return string
     */
    public function getRequiredFilterField() {
        return "";
    }

}
