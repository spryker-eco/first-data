<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Persistence;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use Generated\Shared\Transfer\PaymentFirstDataApiLogTransfer;
use Generated\Shared\Transfer\PaymentFirstDataItemTransfer;
use Generated\Shared\Transfer\PaymentFirstDataTransfer;

interface FirstDataEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataTransfer $paymentFirstDataTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataTransfer
     */
    public function savePaymentFirstData(PaymentFirstDataTransfer $paymentFirstDataTransfer): PaymentFirstDataTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataItemTransfer $paymentFirstDataItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataItemTransfer
     */
    public function savePaymentFirstDataItem(
        PaymentFirstDataItemTransfer $paymentFirstDataItemTransfer
    ): PaymentFirstDataItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataApiLogTransfer $paymentFirstDataApiLogTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataApiLogTransfer
     */
    public function savePaymentFirstDataApiLog(
        PaymentFirstDataApiLogTransfer $paymentFirstDataApiLogTransfer
    ): PaymentFirstDataApiLogTransfer;

    /**
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer
     */
    public function savePaymentFirstDataNotification(
        FirstDataNotificationTransfer $firstDataNotificationTransfer
    ): FirstDataNotificationTransfer;

    /**
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return void
     */
    public function tokenizeClientToken(
        FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return void
     */
    public function attachCardTokenToCustomer(
        CustomerTransfer $customer,
        FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
    ): void;
}
