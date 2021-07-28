<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Persistence;

use Orm\Zed\FirstData\Persistence\SpyCustomerToFirstDataCardTokenQuery;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataApiLogQuery;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataCardTokenQuery;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataItemQuery;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataNotificationQuery;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerEco\Zed\FirstData\FirstDataConfig getConfig()
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface getRepository()
 */
class FirstDataPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery
     */
    public function createOmsOrderItemStateHistoryQuery(): SpyOmsOrderItemStateHistoryQuery
    {
        return SpyOmsOrderItemStateHistoryQuery::create();
    }

    /**
     * @return \Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataQuery
     */
    public function createPaymentFirstDataQuery(): SpyPaymentFirstDataQuery
    {
        return SpyPaymentFirstDataQuery::create();
    }

    /**
     * @return \Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataCardTokenQuery
     */
    public function createPaymentFirstDataCardTokenQuery(): SpyPaymentFirstDataCardTokenQuery
    {
        return SpyPaymentFirstDataCardTokenQuery::create();
    }

    /**
     * @return \Orm\Zed\FirstData\Persistence\SpyCustomerToFirstDataCardTokenQuery
     */
    public function createCustomerToFirstDataCardTokenQuery(): SpyCustomerToFirstDataCardTokenQuery
    {
        return SpyCustomerToFirstDataCardTokenQuery::create();
    }

    /**
     * @return \Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataApiLogQuery
     */
    public function createPaymentFirstDataApiLogQuery(): SpyPaymentFirstDataApiLogQuery
    {
        return SpyPaymentFirstDataApiLogQuery::create();
    }

    /**
     * @return \Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataItemQuery
     */
    public function createPaymentFirstDataItemQuery(): SpyPaymentFirstDataItemQuery
    {
        return SpyPaymentFirstDataItemQuery::create();
    }

    /**
     * @return \Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataNotificationQuery
     */
    public function createSpyPaymentFirstDataNotificationQuery(): SpyPaymentFirstDataNotificationQuery
    {
        return SpyPaymentFirstDataNotificationQuery::create();
    }
}
