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

namespace OnlineShop\Framework\CheckoutManager;

/**
 * Interface \OnlineShop\Framework\CheckoutManager\ICheckoutManager
 */
interface ICheckoutManager {

    /**
     * returns all checkout steps defined for this checkout
     *
     * @return ICheckoutStep[]
     */
    public function getCheckoutSteps();

    /**
     * returns checkout step with given name
     *
     * @param  string $stepName
     * @return ICheckoutStep
     */
    public function getCheckoutStep($stepName);

    /**
     * returns current checkout step
     *
     * @return ICheckoutStep
     */
    public function getCurrentStep();

    /**
     * returns the cart the checkout is started with
     *
     * @return \OnlineShop\Framework\CartManager\ICart
     */
    public function getCart();

    /**
     * commits checkout step
     * all previous steps must be committed, otherwise committing step is not allowed
     *
     * @param ICheckoutStep $step
     * @param  mixed                             $data
     * @return bool
     */
    public function commitStep(ICheckoutStep $step, $data);

    /**
     * checks if checkout is finished (= all checkout steps are committed)
     * only a finished checkout can be committed
     *
     * @return bool
     */
    public function isFinished();

    /**
     * returns if there currently is a active payment
     *
     * @return bool
     */
    public function hasActivePayment();

    /**
     * starts payment for checkout - only possible if payment provider is configured
     * sets cart to read only mode since it must not changed during ongoing payment process
     *
     * @return \OnlineShop\Framework\Model\AbstractPaymentInformation
     */
    public function startOrderPayment();

    /**
     * cancels payment for current payment info
     * - payment will be cancelled, order state will be resetted and cart will we writable again.
     *
     * -> this should be used, when user cancels payment
     *
     * only possible when payment state is PENDING, otherwise exception is thrown
     *
     * @return null|\OnlineShop\Framework\Model\AbstractOrder
     * @throws \OnlineShop\Framework\Exception\UnsupportedException
     */
    public function cancelStartedOrderPayment();

    /**
     * returns order (creates it if not available yet)
     * - delegates to commit order processor
     *
     * @return \OnlineShop\Framework\Model\AbstractOrder
     */
    public function getOrder();


    /**
     * facade method for
     * - handling payment response and
     * - commit order payment
     *
     * use this for committing order when payment is activated
     *
     * delegates to commit order processor
     *
     * @param $paymentResponseParams
     * @return \OnlineShop\Framework\Model\AbstractOrder
     */
    public function handlePaymentResponseAndCommitOrderPayment($paymentResponseParams);

    /**
     * commits order payment
     *   - updates order payment information in order object
     *   - only when payment status == [ORDER_STATE_COMMITTED, ORDER_STATE_PAYMENT_AUTHORIZED] -> order is committed
     *
     * delegates to commit order processor
     *
     * @deprecated use handlePaymentResponseAndCommitOrderPayment instead
     * @param \OnlineShop\Framework\PaymentManager\IStatus $status
     * @return \OnlineShop\Framework\Model\AbstractOrder
     */
    public function commitOrderPayment(\OnlineShop\Framework\PaymentManager\IStatus $status);

    /**
     * commits order - does not consider any payment
     *
     * use this for committing order when no payment is activated
     *
     * delegates to commit order processor
     *
     * @return \OnlineShop\Framework\Model\AbstractOrder
     */
    public function commitOrder();

    /**
     * returns if checkout process and subsequently order is committed
     * basically checks, if order is available and if this order is committed
     *
     * @return bool
     */
    public function isCommitted();

    /**
     * returns payment adapter
     *
     * @return \OnlineShop\Framework\PaymentManager\Payment\IPayment|null
     */
    public function getPayment();

    /**
     * cleans up orders with state pending payment after 1h -> delegates this to commit order processor
     *
     * @return void
     */
    public function cleanUpPendingOrders();
}
