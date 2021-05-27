<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\FirstData\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeRefundTest
 * Add your own group annotations below this line
 */
class FirstDataFacadeRefundTest extends AbstractFirstDataFacadeTest
{
    public const REQUEST_TYPE = 'ReturnTransaction';

    public const TRANSACTION_TYPE = 'RETURN';

    /**
     * @var \PyzTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @var array
     */
    protected $clientResponse;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->clientResponse = $this->tester->getClientResponse();
    }

    /**
     * @return void
     */
    public function testExecuteRefundOmsCommandSuccessCase(): void
    {
        //Arrange
        $clientResponse = $this->tester->getClientResponse();
        $clientResponse['transactionType'] = static::TRANSACTION_TYPE;
        $aggregatedSum = $clientResponse['approvedAmount']['total'] * 100;
        $item = (new ItemTransfer())->setIdSalesOrderItem(1)->setRefundableAmount($aggregatedSum);
        $currency = (new CurrencyTransfer())->setName($clientResponse['approvedAmount']['currency']);
        $order = (new OrderTransfer())->setCurrency($currency);

        $refund = new RefundTransfer();
        $refund->addItem($item);
        $firstDataApiRequestTransfer = new FirstDataApiRequestTransfer();
        $firstDataApiRequestTransfer->setTransactionId($clientResponse['orderId']);
        $firstDataApiRequestTransfer->setRequestType(static::REQUEST_TYPE);
        $firstDataApiRequestTransfer->setOrder($order);
        $firstDataApiRequestTransfer->setRefund($refund);

        //Act
        $response = $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock($clientResponse))
            ->executeRefundOmsCommand($firstDataApiRequestTransfer);

        //Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNull($response->getError());
        $this->assertNotEmpty($response->getClientResponse());
        $this->assertEquals(
            $firstDataApiRequestTransfer->getTransactionId(),
            $response->getClientResponse()->getOrderId()
        );
        $this->assertEquals(
            $firstDataApiRequestTransfer->getOrder()->getCurrency()->getName(),
            $response->getClientResponse()->getApprovedAmount()->getCurrency(),
        );
        $this->assertEquals(
            $firstDataApiRequestTransfer->getRefund()->getItems()[0]->getRefundableAmount() / 100,
            $response->getClientResponse()->getApprovedAmount()->getTotal(),
        );
        $this->assertEquals(static::TRANSACTION_TYPE, $response->getClientResponse()->getTransactionType());
    }
}
