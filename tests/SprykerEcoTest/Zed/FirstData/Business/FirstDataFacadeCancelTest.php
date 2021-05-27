<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\FirstData\Business;

use Generated\Shared\Transfer\FirstDataApiRequestTransfer;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeCancelTest
 * Add your own group annotations below this line
 */
class FirstDataFacadeCancelTest extends AbstractFirstDataFacadeTest
{
    public const REQUEST_TYPE = 'VoidTransaction';

    public const TRANSACTION_TYPE = 'VOID';

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
    public function testExecuteCancelPreAuthOmsCommandSuccessCase(): void
    {
        //Arrange
        $clientResponse = $this->clientResponse;
        $clientResponse['transactionType'] = static::TRANSACTION_TYPE;
        $firstDataApiRequestTransfer = new FirstDataApiRequestTransfer();
        $firstDataApiRequestTransfer->setTransactionId($clientResponse['orderId']);
        $firstDataApiRequestTransfer->setRequestType(static::REQUEST_TYPE);

        //Act
        $response = $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock($clientResponse))
            ->executeCancelOmsCommand($firstDataApiRequestTransfer);

        //Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNull($response->getError());
        $this->assertNotEmpty($response->getClientResponse());
        $this->assertEquals($firstDataApiRequestTransfer->getTransactionId(), $response->getClientResponse()->getOrderId());
        $this->assertEquals(static::TRANSACTION_TYPE, $response->getClientResponse()->getTransactionType());
    }
}
