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
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataApiLog;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataNotification;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataPersistenceFactory getFactory()
 */
class FirstDataEntityManager extends AbstractEntityManager implements FirstDataEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataTransfer $paymentFirstDataTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataTransfer
     */
    public function savePaymentFirstData(PaymentFirstDataTransfer $paymentFirstDataTransfer): PaymentFirstDataTransfer
    {
        $paymentFirstDataEntity = $this->getFactory()
            ->createPaymentFirstDataQuery()
            ->filterByFkSalesOrder($paymentFirstDataTransfer->getFkSalesOrder())
            ->filterByOrderReference($paymentFirstDataTransfer->getOrderReference())
            ->findOneOrCreate();

        $paymentFirstDataEntity->fromArray($paymentFirstDataTransfer->toArray());

        $paymentFirstDataEntity->save();

        return (new PaymentFirstDataTransfer())
            ->fromArray($paymentFirstDataEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return void
     */
    public function tokenizeClientToken(
        FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
    ): void {
        $firstDataCustomerTokenTransfer->requireClientToken();

        $paymentFirstDataCardTokenEntity = $this->getFactory()
            ->createPaymentFirstDataCardTokenQuery()
            ->filterByClientToken($firstDataCustomerTokenTransfer->getClientToken())
            ->findOneOrCreate();

        $paymentFirstDataCardTokenEntity->fromArray($firstDataCustomerTokenTransfer->toArray());

        $paymentFirstDataCardTokenEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return void
     */
    public function attachCardTokenToCustomer(
        CustomerTransfer $customer,
        FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
    ): void {
        $paymentFirstDataCardTokenEntity = $this->getFactory()
            ->createPaymentFirstDataCardTokenQuery()
            ->filterByClientToken($firstDataCustomerTokenTransfer->getClientToken())
            ->findOneOrCreate();

        $paymentFirstDataCardTokenEntity->fromArray($firstDataCustomerTokenTransfer->toArray());

        $paymentFirstDataCardTokenEntity->save();

        $customerToFirstDataCardTokenEntity = $this->getFactory()
            ->createCustomerToFirstDataCardTokenQuery()
            ->filterByCustomerReference($customer->getCustomerReference())
            ->filterByFkPaymentFirstDataCardToken($paymentFirstDataCardTokenEntity->getIdPaymentFirstDataCardToken())
            ->findOneOrCreate();

        $customerToFirstDataCardTokenEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataItemTransfer $paymentFirstDataItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataItemTransfer
     */
    public function savePaymentFirstDataItem(PaymentFirstDataItemTransfer $paymentFirstDataItemTransfer): PaymentFirstDataItemTransfer
    {
        $paymentFirstDataItemEntity = $this->getFactory()
            ->createPaymentFirstDataItemQuery()
            ->filterByFkPaymentFirstData($paymentFirstDataItemTransfer->getFkPaymentFirstData())
            ->filterByFkSalesOrderItem($paymentFirstDataItemTransfer->getFkSalesOrderItem())
            ->findOneOrCreate();

        $paymentFirstDataItemEntity->fromArray($paymentFirstDataItemTransfer->toArray());

        $paymentFirstDataItemEntity->save();

        return (new PaymentFirstDataItemTransfer())
            ->fromArray($paymentFirstDataItemEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataApiLogTransfer $paymentFirstDataApiLogTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataApiLogTransfer
     */
    public function savePaymentFirstDataApiLog(PaymentFirstDataApiLogTransfer $paymentFirstDataApiLogTransfer): PaymentFirstDataApiLogTransfer
    {
        $paymentFirstDataApiLogEntity = new SpyPaymentFirstDataApiLog();
        $paymentFirstDataApiLogEntity->fromArray($paymentFirstDataApiLogTransfer->toArray());

        $paymentFirstDataApiLogEntity->save();

        return (new PaymentFirstDataApiLogTransfer())
            ->fromArray($paymentFirstDataApiLogEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer
     */
    public function savePaymentFirstDataNotification(
        FirstDataNotificationTransfer $firstDataNotificationTransfer
    ): FirstDataNotificationTransfer {
        $paymentFirstDataNotification = new SpyPaymentFirstDataNotification();
        $paymentFirstDataNotification->fromArray($firstDataNotificationTransfer->toArray());
        $paymentFirstDataNotification->save();

        return (new FirstDataNotificationTransfer())
            ->fromArray($paymentFirstDataNotification->toArray(), true);
    }
}
