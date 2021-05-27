<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\FirstData\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use PyzTest\Zed\FirstData\FirstDataBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeCaptureTest
 * Add your own group annotations below this line
 */
class FirstDataFacadeCaptureTest extends AbstractFirstDataFacadeTest
{
    public const TRANSACTION_TYPE = 'POSTAUTH';

    /**
     * @var \PyzTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @var array
     */
    protected $clientResponse;

    /**
     * @var \Pyz\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $salesOrderEntity;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected $salesOrderItemEntity;

    /**
     * @var \Orm\Zed\FirstData\Persistence\SpyPaymentFirstData
     */
    protected $paymentFirstDataEntity;

    /**
     * @var \Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataItem
     */
    protected $paymentFirstDataItemEntity;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->clientResponse = $this->tester->getClientResponse();
        $this->firstDataConfig = $this->tester->createConfig();
        $this->salesOrderEntity = $this->tester->createTestSalesOrderEntity();
        $this->salesOrderItemEntity = $this->tester->createTestSalesOrderItemEntity($this->salesOrderEntity);
        $this->paymentFirstDataEntity = $this->tester->createTestPaymentFirstDataEntity($this->salesOrderEntity);
        $this->paymentFirstDataItemEntity = $this->tester->createTestPaymentFirstDataItemEntity(
            $this->paymentFirstDataEntity,
            $this->salesOrderItemEntity,
            $this->firstDataConfig->getOmsStatusAuthorized()
        );
    }

    /**
     * @skip
     *
     * @return void
     */
    public function testExecuteCaptureOmsCommandSuccessCase(): void
    {
        //Arrange
        $clientResponse = $this->tester->getClientResponse();
        $clientResponse['orderId'] = $this->salesOrderEntity->getIdSalesOrder();
        $clientResponse['transactionType'] = static::TRANSACTION_TYPE;
        $clientResponse['ipgTransactionId'] = FirstDataBusinessTester::ID_TRANSACTION;

        $currencyTransfer = (new CurrencyTransfer())
            ->setName($this->salesOrderEntity->getCurrencyIsoCode())
            ->setCode($this->salesOrderEntity->getCurrencyIsoCode());

        $orderTransfer = (new OrderTransfer())
            ->fromArray($this->salesOrderEntity->toArray(), true)
            ->setCurrency($currencyTransfer);

        //Act
//        $this->tester
//            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock($clientResponse))
//            ->executeCaptureOmsCommand();
    }
}
