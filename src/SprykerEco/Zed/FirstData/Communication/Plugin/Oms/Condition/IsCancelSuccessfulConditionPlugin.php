<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \SprykerEco\Zed\FirstData\Communication\FirstDataCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\FirstData\Business\FirstDataFacadeInterface getFacade()
 * @method \SprykerEco\Zed\FirstData\FirstDataConfig getConfig()
 */
class IsCancelSuccessfulConditionPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `SalesOrderItem.PaymentFirstDataItem` has status "canceled".
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        if ($orderItem->getPaymentFirstDataItems()->isEmpty()) {
            return false;
        }

        $firstDataItem = $orderItem->getPaymentFirstDataItems()->getLast();

        return $firstDataItem->getStatus() === $this->getConfig()->getOmsStatusCanceled();
    }
}
