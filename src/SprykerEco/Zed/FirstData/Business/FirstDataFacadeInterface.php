<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
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
     * - Saves `cardToken` to DB and associate it with current customer.
     * - Returns an error if the operation couldn't be executed successfully.
     * - Logs request's details to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executeReservationRequest(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void;

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
     * - Logs request's details to DB.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer
     *
     * @return void
     */
    public function executeRefundOmsCommand(FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer): void;

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

    /**
     * Specification:
     * - Makes API call to the First Data in order to receive authorize session data `ClientToken` and `PublicKeyBase64`.
     * - Associate `ClientToken` with current customer save association to database.
     * - Returns response from api.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(CustomerTransfer $customerTransfer): FirstDataApiResponseTransfer;

    /**
     * Specification:
     * - Reads all available card tokens from DB for given customer.
     * - Returns customer tokens collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer
     */
    public function getFirstDataCustomerTokensCollection(CustomerTransfer $customerTransfer): FirstDataCustomerTokensCollectionTransfer;

    /**
     * Specification:
     * - Retrieves token by `ClientToken` from database.
     * - Saves first data payment token data to database.
     * - Returns customer token back.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer
     */
    public function processTokenization(FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer): FirstDataCustomerTokenTransfer;
}
