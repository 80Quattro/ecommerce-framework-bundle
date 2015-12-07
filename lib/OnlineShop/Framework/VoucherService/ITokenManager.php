<?php
/**
 * Pimcore
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2015 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GNU General Public License version 3 (GPLv3)
 */


/**
 * Interface OnlineShop_Framework_VoucherService_ITokenManager
 *
 * Manager for specific token settings object of type \OnlineShop\Framework\Model\AbstractVoucherTokenType.
 * Provides functionality for generating, checking, removing, applying, reserving tokens.
 * Also prepares the view of the Voucher Code Tab and specifies its template.
 */
interface OnlineShop_Framework_VoucherService_ITokenManager
{
    /**
     * @param \OnlineShop\Framework\Model\AbstractVoucherTokenType $configuration
     */
    public function __construct(\OnlineShop\Framework\Model\AbstractVoucherTokenType $configuration);

    /**
     * Checks if the configuration objects contains valid values to
     * generate the new token(s).
     *
     * @return bool
     */
    public function isValidSetting();

    /**
     * Removes tokens of series, if no parameters are passed, all tokens get removed from series.
     *
     * @param array|null $filter Associative with the indices: "usage" and "olderThan".
     *
     * @return mixed
     */
    public function cleanUpCodes($filter = []);

    /**
     * Checks a token code, if it is available for putting into cart
     * e.g. it is not reserved or used, or other tokenType specific settings.
     *
     * @param string $code
     * @param \OnlineShop\Framework\CartManager\ICart $cart
     * @throws Exception
     * @return bool
     */
    public function checkToken($code, \OnlineShop\Framework\CartManager\ICart $cart);

    /**
     * Adds a reservation to a specific token code.
     *
     * @param string $code
     * @param \OnlineShop\Framework\CartManager\ICart $cart
     *
     * @throws Exception
     *
     * @return bool
     */
    public function reserveToken($code, \OnlineShop\Framework\CartManager\ICart $cart);

    /**
     * Creates token object and adds it to order, increases token usage and
     * clears the reservation of the token.
     *
     * @param string $code
     * @param \OnlineShop\Framework\CartManager\ICart $cart
     * @param \OnlineShop\Framework\Model\AbstractOrder $order
     *
     * @throws Exception
     *
     * @return bool|\Pimcore\Model\Object\OnlineShopVoucherToken
     */
    public function applyToken($code, \OnlineShop\Framework\CartManager\ICart $cart, \OnlineShop\Framework\Model\AbstractOrder $order);

    /**
     * Removes the reservation of a token code.
     *
     * @param string $code
     * @param \OnlineShop\Framework\CartManager\ICart $cart
     * @return bool
     */
    public function releaseToken($code, \OnlineShop\Framework\CartManager\ICart $cart);

    /**
     * cleans up the token usage and the ordered token object if necessary
     *
     * @param \Pimcore\Model\Object\OnlineShopVoucherToken $tokenObject
     * @param \OnlineShop\Framework\Model\AbstractOrder $order
     * @return bool
     */
    public function removeAppliedTokenFromOrder(\Pimcore\Model\Object\OnlineShopVoucherToken $tokenObject, \OnlineShop\Framework\Model\AbstractOrder $order);

    /**
     * Get the codes of a voucher series, optionally a filter array can be passed.
     *
     * @param array|null $filter
     * @return array|bool
     */
    public function getCodes($filter = null);

    /**
     * @param null|int $usagePeriod
     * @return bool|array
     */
    public function getStatistics($usagePeriod = null);

    /**
     * @return bool
     */
    public function insertOrUpdateVoucherSeries();

    /**
     * @return  int
     */
    public function getFinalTokenLength();

    /**
     * @param int $duration
     * @return bool
     */
    public function cleanUpReservations($duration = 0);

    /**
     * Prepares the view and returns the according template for rendering.
     * Gets the codes according to paging and filter params and sets
     * error/success messages, settings and statistics for view.
     *
     * @param $view
     * @param array $params All params, especially for filtering and ordering token codes.
     * @return string The path of the template to display.
     */
    public function prepareConfigurationView($view, $params);


    /**
     * @return \OnlineShop\Framework\Model\AbstractVoucherTokenType
     */
    public function getConfiguration();
}