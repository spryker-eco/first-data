<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \SprykerEco\Zed\FirstData\Business\FirstDataFacadeInterface getFacade()
 * @method \SprykerEco\Zed\FirstData\FirstDataConfig getConfig()
 * @method \SprykerEco\Zed\FirstData\Communication\FirstDataCommunicationFactory getFactory()
 */
class FirstDataRefundCommandByOrderPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Makes ReturnTransaction request to FirstData API and refunds applicable items.
     * - Logs request's details to DB.
     * - Updates order items status into DB.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): array
    {
        $salesOrderItemIds = [];
        foreach ($orderItems as $salesOrderItemEntity) {
            $salesOrderItemIds[] = $salesOrderItemEntity->getIdSalesOrderItem();
        }

        $orderTransfer = $this->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder($orderEntity->getIdSalesOrder());

        $this->getFacade()->executeRefundOmsCommand(
            (new FirstDataOmsCommandRequestTransfer())
                ->setOrder($orderTransfer)
                ->setSalesOrderItemIds($salesOrderItemIds)
        );

        return [];
    }
}
