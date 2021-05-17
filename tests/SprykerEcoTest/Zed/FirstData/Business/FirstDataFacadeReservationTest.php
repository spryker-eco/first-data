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
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTokenTransfer;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeReservationTest
 * Add your own group annotations below this line
 */
class FirstDataFacadeReservationTest extends AbstractFirstDataFacadeTest
{
    public const REQUEST_TYPE = 'PaymentTokenPreAuthTransaction';

    public const TRANSACTION_TYPE = 'PREAUTH';

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
    public function testExecuteReservationOmsCommandSuccessCase(): void
    {
        //Arrange
        $clientResponse = $this->tester->getClientResponse();
        $clientResponse['transactionType'] = static::TRANSACTION_TYPE;
        $aggregatedSum = $clientResponse['approvedAmount']['total'] * 100;
        $item = (new ItemTransfer())->setIdSalesOrderItem(1)->setSumPriceToPayAggregation($aggregatedSum);
        $currency = (new CurrencyTransfer())->setName($clientResponse['approvedAmount']['currency']);
        $order = (new OrderTransfer())->setCurrency($currency)->addItem($item);
        $firstDataApiRequestTransfer = new FirstDataApiRequestTransfer();
        $firstDataApiRequestTransfer->setRequestType(static::REQUEST_TYPE);
        $firstDataApiRequestTransfer->setOrder($order);
        $firstDataApiRequestTransfer->setOrderItemIds([1]);
        $firstDataApiRequestTransfer->setPaymentMethod(
            (new PaymentMethodTransfer())
                ->setPaymentToken((new PaymentTokenTransfer())
                    ->setValue('21EEEE8A-398C-4D9A-B5D6-F1A6273F933F')->setFunction('CREDIT'))
        );
        $firstDataApiRequestTransfer->setStoreName('12022224560');
        $firstDataApiRequestTransfer->setPercentageBuffer(0);

        //Act
        $response = $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock($clientResponse))
            ->executeReservationOmsCommand($firstDataApiRequestTransfer);

        //Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNull($response->getError());
        $this->assertNotEmpty($response->getClientResponse());
        $this->assertEquals(
            $firstDataApiRequestTransfer->getOrder()->getCurrency()->getName(),
            $response->getClientResponse()->getApprovedAmount()->getCurrency(),
        );

        $this->assertEquals(
            $firstDataApiRequestTransfer->getOrder()->getItems()[0]->getSumPriceToPayAggregation() / 100,
            $response->getClientResponse()->getApprovedAmount()->getTotal(),
        );
        $this->assertEquals(static::TRANSACTION_TYPE, $response->getClientResponse()->getTransactionType());
    }
}
