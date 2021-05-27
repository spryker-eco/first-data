<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Persistence;

use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use Generated\Shared\Transfer\PaymentFirstDataItemTransfer;
use Generated\Shared\Transfer\PaymentFirstDataTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataPersistenceFactory getFactory()
 */
class FirstDataRepository extends AbstractRepository implements FirstDataRepositoryInterface
{
    /**
     * @param string $transactionId
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer|null
     */
    public function findPaymentFirstDataNotificationByTransactionId(string $transactionId): ?FirstDataNotificationTransfer
    {
        $paymentFirstDataNotificationEntity = $this->getFactory()
            ->createSpyPaymentFirstDataNotificationQuery()
            ->filterByTransactionId($transactionId)
            ->findOne();

        if (!$paymentFirstDataNotificationEntity) {
            return null;
        }

        return (new FirstDataNotificationTransfer())
            ->fromArray($paymentFirstDataNotificationEntity->toArray(), true);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataTransfer|null
     */
    public function findPaymentFirstDataByIdSalesOrderItem(int $idSalesOrderItem): ?PaymentFirstDataTransfer
    {
        $paymentFirstDataEntity = $this->getFactory()
            ->createPaymentFirstDataQuery()
            ->usePaymentFirstDataItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->findOne();

        if (!$paymentFirstDataEntity) {
            return null;
        }

        return (new PaymentFirstDataTransfer())
            ->fromArray($paymentFirstDataEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataTransfer $paymentFirstDataTransfer
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataItemTransfer[]
     */
    public function getPaymentFirstDataItemCollection(
        PaymentFirstDataTransfer $paymentFirstDataTransfer,
        array $salesOrderItemIds = []
    ): array {
        $paymentFirstDataItemEntityCollection = $this->getFactory()
            ->createPaymentFirstDataItemQuery()
            ->filterByFkPaymentFirstData($paymentFirstDataTransfer->getIdPaymentFirstData());

        if ($salesOrderItemIds) {
            $paymentFirstDataItemEntityCollection->filterByFkSalesOrderItem_In($salesOrderItemIds);
        }

        $paymentFirstDataItemEntityCollection->find();

        if (!$paymentFirstDataItemEntityCollection->count()) {
            return [];
        }

        $paymentFirstDataItemTransfers = [];
        foreach ($paymentFirstDataItemEntityCollection as $paymentFirstDataItemEntity) {
            $paymentFirstDataItemTransfers[] = (new PaymentFirstDataItemTransfer())
                ->fromArray($paymentFirstDataItemEntity->toArray(), true);
        }

        return $paymentFirstDataItemTransfers;
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $stateName
     *
     * @return int
     */
    public function countOmsOrderItemStateHistoryByStateNameAndIdSalesOrderItem(int $idSalesOrderItem, string $stateName): int
    {
        return $this->getFactory()
            ->createOmsOrderItemStateHistoryQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->useStateQuery()
                ->filterByName($stateName)
            ->endUse()
            ->count();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataTransfer|null
     */
    public function findPaymentFirstDataByIdSalesOrder(int $idSalesOrder): ?PaymentFirstDataTransfer
    {
        $paymentFirstDataEntity = $this->getFactory()
            ->createPaymentFirstDataQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->findOne();

        if (!$paymentFirstDataEntity) {
            return null;
        }

        return (new PaymentFirstDataTransfer())
            ->fromArray($paymentFirstDataEntity->toArray(), true);
    }
}
