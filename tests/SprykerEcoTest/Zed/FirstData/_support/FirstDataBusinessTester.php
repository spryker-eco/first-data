<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\FirstData;

use Codeception\Actor;
use DateTime;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\DataBuilder\RestPaymentBuilder;
use Generated\Shared\Transfer\FirstDataTransactionDataTransfer;
use Generated\Shared\Transfer\FirstDataTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstData;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataItem;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataItemQuery;
use Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataNotification;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Pyz\Zed\FirstData\Business\FirstDataBusinessFactory;
use Pyz\Zed\FirstData\Business\FirstDataFacade;
use Pyz\Zed\FirstData\Business\FirstDataFacadeInterface;
use Pyz\Zed\FirstData\Communication\Plugin\Checkout\FirstDataCheckoutDoSaveOrderPlugin;
use Pyz\Zed\FirstData\FirstDataConfig;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class FirstDataBusinessTester extends Actor
{
    use _generated\FirstDataBusinessTesterActions;

    public const ID_TRANSACTION = '84556522425';
    public const OID = '2bfaf81a-3435';

    /**
     * @var array
     */
    protected $clientResponse = [
        'clientRequestId' => '2bfaf81a-3435-4c57-b03a-e87518479ca0',
        'apiTraceId' => 'rrt-0ce9b27534d1d00fc-b-de-22421-79415305-3',
        'ipgTransactionId' => '84556522425',
        'orderId' => 'R-6afe8368-b538-4596-9c69-f725bc03ce51',
        'transactionOrigin' => 'ECOM',
        'paymentMethodDetails' => [
            'paymentCard' => [
                'expiryDate' => [
                    'month' => '10',
                    'year' => '2022',
                ],
                'bin' => '520474',
                'last4' => '1002',
                'brand' => 'MASTERCARD',
            ],
            'paymentMethodType' => 'PAYMENT_CARD',
        ],
        'country' => 'United Kingdom',
        'terminalId' => '1609839',
        'merchantId' => '1218419',
        'transactionTime' => 1613670277,
        'approvedAmount' => [
            'total' => 27710,
            'currency' => 'USD',
            'components' => [
                'subtotal' => 27710,
            ],
        ],
        'transactionStatus' => 'APPROVED',
        'schemeTransactionId' => '0218MCC768928',
        'processor' => [
            'referenceNumber' => '84556522403 ',
            'authorizationCode' => 'OK9432',
            'responseCode' => '00',
            'network' => 'MAST',
            'associationResponseCode' => '',
            'responseMessage' => 'APPROVAL',
            'avsResponse' => [
                'streetMatch' => 'NO_INPUT_DATA',
                'postalCodeMatch' => 'NO_INPUT_DATA',
            ],
            'transactionIntegrityClass' => 'A2',
        ],
    ];

    /**
     * @return \Pyz\Zed\FirstData\FirstDataConfig
     */
    public function createConfig(): FirstDataConfig
    {
        return new FirstDataConfig();
    }

    /**
     * @return array
     */
    public function getClientResponse(): array
    {
        return $this->clientResponse;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function createSalesOrderAddressEntity(): SpySalesOrderAddress
    {
        $salesOrderAddressEntity = new SpySalesOrderAddress();
        $salesOrderAddressEntity->setAddress1('1');
        $salesOrderAddressEntity->setAddress2('2');
        $salesOrderAddressEntity->setSalutation('Mr');
        $salesOrderAddressEntity->setCellPhone('123456789');
        $salesOrderAddressEntity->setCity('City');
        $salesOrderAddressEntity->setCreatedAt(new DateTime());
        $salesOrderAddressEntity->setUpdatedAt(new DateTime());
        $salesOrderAddressEntity->setComment('Comment');
        $salesOrderAddressEntity->setDescription('Description');
        $salesOrderAddressEntity->setCompany('Company');
        $salesOrderAddressEntity->setFirstName('FirstName');
        $salesOrderAddressEntity->setLastName('LastName');
        $salesOrderAddressEntity->setFkCountry(1);
        $salesOrderAddressEntity->setEmail('Email');
        $salesOrderAddressEntity->setZipCode('12345');
        $salesOrderAddressEntity->save();

        return $salesOrderAddressEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    protected function createOmsStateEntity(): SpyOmsOrderItemState
    {
        $omsStateEntity = new SpyOmsOrderItemState();
        $omsStateEntity->setName('test');
        $omsStateEntity->save();

        return $omsStateEntity;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createTestSalesOrderEntity(): SpySalesOrder
    {
        $salesOrderEntity = new SpySalesOrder();
        $salesOrderEntity->setFkSalesOrderAddressBilling($this->createSalesOrderAddressEntity()->getIdSalesOrderAddress());
        $salesOrderEntity->setFkSalesOrderAddressShipping($this->createSalesOrderAddressEntity()->getIdSalesOrderAddress());
        $salesOrderEntity->setOrderReference('order reference');
        $salesOrderEntity->setCurrencyIsoCode('USD');
        $salesOrderEntity->save();

        return $salesOrderEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function createTestSalesOrderItemEntity(SpySalesOrder $salesOrderEntity): SpySalesOrderItem
    {
        $salesOrderItemEntity = new SpySalesOrderItem();
        $salesOrderItemEntity->setFkOmsOrderItemState($this->createOmsStateEntity()->getIdOmsOrderItemState());
        $salesOrderItemEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderItemEntity->setGrossPrice(27710);
        $salesOrderItemEntity->setPriceToPayAggregation(27710);
        $salesOrderItemEntity->setName('name-of-order-item');
        $salesOrderItemEntity->setSku('sku-123-321');
        $salesOrderItemEntity->save();

        return $salesOrderItemEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Orm\Zed\FirstData\Persistence\SpyPaymentFirstData
     */
    public function createTestPaymentFirstDataEntity(SpySalesOrder $salesOrderEntity): SpyPaymentFirstData
    {
        $paymentFirstDataEntity = new SpyPaymentFirstData();
        $paymentFirstDataEntity
            ->setFkSalesOrder($salesOrderEntity->getIdSalesOrder())
            ->setTransactionId(static::ID_TRANSACTION)
            ->setOrderReference($salesOrderEntity->getOrderReference())
            ->setOid(static::OID)
            ->save();

        return $paymentFirstDataEntity;
    }

    /**
     * @param \Orm\Zed\FirstData\Persistence\SpyPaymentFirstData $paymentFirstDataEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param string $status
     *
     * @return \Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataItem
     */
    public function createTestPaymentFirstDataItemEntity(SpyPaymentFirstData $paymentFirstDataEntity, SpySalesOrderItem $salesOrderItemEntity, string $status): SpyPaymentFirstDataItem
    {
        $paymentFirstDataItemEntity = new SpyPaymentFirstDataItem();
        $paymentFirstDataItemEntity
            ->setFkPaymentFirstData($paymentFirstDataEntity->getIdPaymentFirstData())
            ->setFkSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem())
            ->setStatus($status)
            ->setTransactionId(static::ID_TRANSACTION)
            ->save();

        return $paymentFirstDataItemEntity;
    }

    /**
     * @param \Orm\Zed\FirstData\Persistence\SpyPaymentFirstData $paymentFirstDataEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param string $status
     *
     * @return \Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataItem
     */
    public function findTestPaymentFirstDataItemEntity(SpyPaymentFirstData $paymentFirstDataEntity, SpySalesOrderItem $salesOrderItemEntity, string $status): SpyPaymentFirstDataItem
    {
        return (SpyPaymentFirstDataItemQuery::create())
            ->filterByFkPaymentFirstData($paymentFirstDataEntity->getIdPaymentFirstData())
            ->filterByFkSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem())
            ->filterByStatus($status)
            ->findOne();
    }

    /**
     * @param \Pyz\Zed\FirstData\Business\FirstDataBusinessFactory $firstDataBusinessFactoryMock
     *
     * @return \Pyz\Zed\FirstData\Business\FirstDataFacadeInterface
     */
    public function getFirstDataFacade(FirstDataBusinessFactory $firstDataBusinessFactoryMock): FirstDataFacadeInterface
    {
        $firstDataFacade = $this->getFacade();
        $firstDataFacade->setFactory($firstDataBusinessFactoryMock);

        return $firstDataFacade;
    }

    /**
     * @return \Pyz\Zed\FirstData\Business\FirstDataFacade
     */
    public function getFacade(): FirstDataFacade
    {
        return new FirstDataFacade();
    }

    /**
     * @param string $stateMachineProcessName
     * @param string $transactionId
     * @param string $oid
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function createSalesOrderItemEntityFromQuote(
        string $stateMachineProcessName,
        string $transactionId,
        string $oid
    ): SpySalesOrderItem {
        $storeTransfer = $this->haveStore([StoreTransfer::NAME => 'DE']);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->withPayment()
            ->build();

        $quoteTransfer
            ->setCustomer($this->haveCustomer())
            ->setStore($storeTransfer)
            ->setPayment($this->createPaymentTransfer($transactionId, $oid));

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName, [new FirstDataCheckoutDoSaveOrderPlugin()]);

        return $this->findSalesOrderItemEntity($saveOrderTransfer);
    }

    /**
     * @param string $transactionId
     * @param string $oid
     *
     * @return \Orm\Zed\FirstData\Persistence\SpyPaymentFirstDataNotification
     */
    public function createTestPaymentFirstDataNotificationEntity(string $transactionId, string $oid): SpyPaymentFirstDataNotification
    {
        $paymentFirstDataNotificationEntity = new SpyPaymentFirstDataNotification();
        $paymentFirstDataNotificationEntity
            ->setTransactionId($transactionId)
            ->setOid($oid)
            ->save();

        return $paymentFirstDataNotificationEntity;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())->build();

        return $quoteTransfer;
    }

    /**
     * @param string $transactionId
     * @param string $oid
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createRestCheckoutRequestAttributesTransferWithSinglePayment(string $transactionId, string $oid): RestCheckoutRequestAttributesTransfer
    {
        $firstDataTransactionDataTransfer = (new FirstDataTransactionDataTransfer())
            ->setTransactionId($transactionId)
            ->setOid($oid);

        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withPayment($this->createRestPaymentBuilder())
            ->build()
            ->setFirstDataTransactionData($firstDataTransactionDataTransfer);

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @param string $transactionId
     * @param string $oid
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createPaymentTransfer(string $transactionId, string $oid): PaymentTransfer
    {
        $firstDataTransfer = (new FirstDataTransfer())
            ->setFirstDataTransactionData((new FirstDataTransactionDataTransfer())
                ->setOid($oid)
                ->setTransactionId($transactionId));

        return (new PaymentTransfer())
            ->setPaymentMethod('firstDataCreditCard')
            ->setPaymentProvider('firstData')
            ->setFirstDataCreditCard($firstDataTransfer);
    }

    /**
     * @return \Generated\Shared\DataBuilder\RestPaymentBuilder
     */
    protected function createRestPaymentBuilder(): RestPaymentBuilder
    {
        return (new RestPaymentBuilder([
            'paymentProvider' => 'firstDataCreditCard',
            'paymentMethod' => 'firstData',
            'paymentSelection' => 'firstDataCreditCard',
        ]));
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function findSalesOrderItemEntity(SaveOrderTransfer $saveOrderTransfer): SpySalesOrderItem
    {
        return (new SpySalesOrderItemQuery())
            ->joinWithOrder()
            ->useOrderQuery()
            ->filterByOrderReference($saveOrderTransfer->getOrderReference())
            ->endUse()
            ->findOne();
    }
}
