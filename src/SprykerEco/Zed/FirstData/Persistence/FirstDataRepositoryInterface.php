<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Persistence;

use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use Generated\Shared\Transfer\PaymentFirstDataTransfer;

interface FirstDataRepositoryInterface
{
    /**
     * @param string $transactionId
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer|null
     */
    public function findPaymentFirstDataNotificationByTransactionId(string $transactionId): ?FirstDataNotificationTransfer;

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataTransfer|null
     */
    public function findPaymentFirstDataByIdSalesOrderItem(int $idSalesOrderItem): ?PaymentFirstDataTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataTransfer $paymentFirstDataTransfer
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataItemTransfer[]
     */
    public function getPaymentFirstDataItemCollection(
        PaymentFirstDataTransfer $paymentFirstDataTransfer,
        array $salesOrderItemIds = []
    ): array;

    /**
     * @param int $idSalesOrderItem
     * @param string $stateName
     *
     * @return int
     */
    public function countOmsOrderItemStateHistoryByStateNameAndIdSalesOrderItem(int $idSalesOrderItem, string $stateName): int;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataTransfer|null
     */
    public function findPaymentFirstDataByIdSalesOrder(int $idSalesOrder): ?PaymentFirstDataTransfer;
}
