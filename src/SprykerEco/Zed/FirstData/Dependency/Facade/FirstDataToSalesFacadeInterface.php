<?php
/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

interface FirstDataToSalesFacadeInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder);
}
