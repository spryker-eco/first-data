<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface FirstDataFacadeInterface
{
    /**
     * Specification:
     * - Saves PaymentFirstData for Order and PaymentFirstDataItem for OrderItems if PaymentProvider for Quote Payment is "FirstData".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function savePaymentEntities(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     * - Makes PaymentTokenPreAuthTransaction request to FirstData API and reserves applicable items.
     * - Returns an error if the operation couldn't be executed successfully.
     * - Logs request's details to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function executeReservationOmsCommand(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): FirstDataApiResponseTransfer;

    /**
     * Specification:
     * - Makes VoidPreAuthTransactions request to FirstData API and reverts PreAuthTransaction.
     * - Logs request's details to DB.
     * - Updates order items status into DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer
     *
     * @return void
     */
    public function executeCancelReservationOmsCommand(FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer): void;

    /**
     * Specification:
     * - Makes PostAuthTransaction request to FirstData API and charges applicable items.
     * - Logs request's details to DB.
     * - Updates order items status in DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer
     *
     * @return void
     */
    public function executeCaptureOmsCommand(FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer): void;

    /**
     * Specification:
     * - Makes VoidTransaction request to FirstData API and reverts PostAuthTransaction.
     * - Returns an error if the operation couldn't be executed successfully.
     * - Logs request's details to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function executeCancelOmsCommand(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): FirstDataApiResponseTransfer;

    /**
     * Specification:
     * - Makes ReturnTransaction request to FirstData API and refunds refundable items.
     * - Returns an error if the operation couldn't be executed successfully.
     * - Logs request's details to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function executeRefundOmsCommand(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): FirstDataApiResponseTransfer;

    /**
     * Specification:
     * - Maps rest request payments to quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapFirstDataPaymentToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer;

    /**
     * Specification:
     * - Processes FirstDataNotificationTransfer saving to database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer
     */
    public function processNotification(FirstDataNotificationTransfer $firstDataNotificationTransfer): FirstDataNotificationTransfer;

    /**
     * Specification:
     * - Checks if FirstDataNotification exists with given `transactionId` in the database.
     * - Returns true if notification has APPROVED status.
     *
     * @api
     *
     * @param string $transactionId
     *
     * @return bool
     */
    public function checkIfApprovedNotificationReceived(string $transactionId): bool;

    /**
     * Specification:
     * - Checks if payment authorization is timed out.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkPaymentAuthorizationTimeOut(OrderTransfer $orderTransfer): bool;

    /**
     * Specification:
     * - Checks if FirstData response is valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkFirstDataResponse(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;

    /**
     * Specification:
     * - Expands order with payment data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function loadPaymentDataByOrder(OrderTransfer $orderTransfer): OrderTransfer;
}
