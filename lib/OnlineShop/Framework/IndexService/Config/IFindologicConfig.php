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


namespace OnlineShop\Framework\IndexService\Config;

/**
 * Interface for IndexService Tenant Configurations using factfinder as index
 *
 * Interface \OnlineShop\Framework\IndexService\Config\IFindologicConfig
 */
interface IFindologicConfig extends IConfig
{
    /**
     * returns factfinder client parameters defined in the tenant config
     *
     * @param string $setting
     *
     * @return array|string
     */
    public function getClientConfig($setting = null);

    /**
     * returns condition for current subtenant
     *
     * @return string
     */
    public function getSubTenantCondition();


    /**
     * creates and returns tenant worker suitable for this tenant configuration
     *
     * @return \OnlineShop\Framework\IndexService\Worker\ElasticSearch
     */
    public function getTenantWorker();

}