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


namespace OnlineShop\Framework\PriceSystem\TaxManagement;


class TaxEntry {

    const CALCULATION_MODE_COMBINE = "combine";
    const CALCULATION_MODE_ONE_AFTER_ANOTHER = "oneAfterAnother";
    const CALCULATION_MODE_FIXED = "fixed";


    /**
     * @var \Pimcore\Model\Object\Fieldcollection\Data\TaxEntry
     */
    protected $entry;

    /**
     * @var float
     */
    protected $percent;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $taxId;

    /**
     * @return \Pimcore\Model\Object\Fieldcollection\Data\TaxEntry
     */
    public function getEntry()
    {
        return $this->entry;
    }


    /**
     * TaxEntry constructor.
     * @param $percent
     * @param $amount
     * @param \Pimcore\Model\Object\Fieldcollection\Data\TaxEntry|null $entry
     */
    public function __construct($percent, $amount, $taxId = null, \Pimcore\Model\Object\Fieldcollection\Data\TaxEntry $entry = null)
    {
        $this->percent = $percent;
        $this->amount = $amount;
        $this->taxId = $taxId;
        $this->entry = $entry;
    }

    /**
     * @param \Pimcore\Model\Object\Fieldcollection\Data\TaxEntry $entry
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param float $percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getTaxId()
    {
        return $this->taxId;
    }

    /**
     * @param string $taxId
     */
    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;
    }


}