<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\FirstData\Business;

use Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEcoTest\Zed\FirstData\FirstDataBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerEcoTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeCaptureTest
 */
class FirstDataFacadeCaptureTest extends AbstractFirstDataFacadeTest
{
    public const TRANSACTION_TYPE = 'POSTAUTH';
    protected const TEST_APPROVED_STATUS = 'TEST_APPROVED_STATUS';
    protected const OMS_STATUS_CAPTURED = 'captured';

    /**
     * @var \SprykerEcoTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteCaptureOmsCommandMustUpdateStatusAfterSuccessRequest(): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->createTestSalesOrderEntity();
        $salesOrderItemEntity = $this->tester->createTestSalesOrderItemEntity($salesOrderEntity);
        $paymentFirstDataEntity = $this->tester->createTestPaymentFirstDataEntity($salesOrderEntity);
        $paymentFirstDataItemEntity = $this->tester->createTestPaymentFirstDataItemEntity(
            $paymentFirstDataEntity,
            $salesOrderItemEntity,
            static::TEST_APPROVED_STATUS
        );

        $clientResponse = $this->tester->getClientResponse();
        $clientResponse['orderId'] = $salesOrderEntity->getIdSalesOrder();
        $clientResponse['transactionType'] = static::TRANSACTION_TYPE;
        $clientResponse['ipgTransactionId'] = FirstDataBusinessTester::ID_TRANSACTION;
        $totals = (new TotalsTransfer())->setGrandTotal($clientResponse['approvedAmount']['total']);

        $orderTransfer = (new OrderTransfer())
            ->fromArray($salesOrderEntity->toArray(), true)
            ->setTotals($totals);

        $firstDataOmsCommandRequestTransfer = (new FirstDataOmsCommandRequestTransfer())
            ->setSalesOrderItemIds([$salesOrderItemEntity->getIdSalesOrderItem()])
            ->setOrder($orderTransfer);

        //Act
        $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock($clientResponse))
            ->executeCaptureOmsCommand($firstDataOmsCommandRequestTransfer);

        $paymentFirstDataItemEntityAfterRefund = $this->tester
            ->findTestPaymentFirstDataItemEntityById($paymentFirstDataItemEntity->getIdPaymentFirstDataItem());

        //Assert
        $this->assertSame(static::OMS_STATUS_CAPTURED, $paymentFirstDataItemEntityAfterRefund->getStatus());
    }
}
