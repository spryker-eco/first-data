<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\FirstData\Business;

use Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerEcoTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeRefundTest
 */
class FirstDataFacadeRefundTest extends AbstractFirstDataFacadeTest
{
    protected const TEST_APPROVED_STATUS = 'TEST_APPROVED_STATUS';
    protected const OMS_STATUS_REFUNDED = 'refunded';

    /**
     * @var \SprykerEcoTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteRefundOmsCommandMustUpdateStatusAfterSuccessRequest(): void
    {
        //Arrange
        $clientResponse = $this->tester->getClientResponse();
        $salesOrderEntity = $this->tester->createTestSalesOrderEntity();
        $salesOrderItemEntity = $this->tester->createTestSalesOrderItemEntity($salesOrderEntity);
        $paymentFirstDataEntity = $this->tester->createTestPaymentFirstDataEntity($salesOrderEntity);
        $paymentFirstDataItemEntity = $this->tester->createTestPaymentFirstDataItemEntity(
            $paymentFirstDataEntity,
            $salesOrderItemEntity,
            static::TEST_APPROVED_STATUS
        );

        $firstDataOmsCommandRequestTransfer = (new FirstDataOmsCommandRequestTransfer())
            ->setSalesOrderItemIds([$salesOrderItemEntity->getIdSalesOrderItem()])
            ->setOrder(
                (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true)
            );

        //Act
        $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock($clientResponse))
            ->executeRefundOmsCommand($firstDataOmsCommandRequestTransfer);

        $paymentFirstDataItemEntityAfterRefund = $this->tester
            ->findTestPaymentFirstDataItemEntityById($paymentFirstDataItemEntity->getIdPaymentFirstDataItem());

        //Assert
        $this->assertSame(static::OMS_STATUS_REFUNDED, $paymentFirstDataItemEntityAfterRefund->getStatus());
    }
}
