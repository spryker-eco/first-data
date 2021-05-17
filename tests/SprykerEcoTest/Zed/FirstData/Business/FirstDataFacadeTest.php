<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\FirstData\Business;

use ArrayObject;
use DateInterval;
use DateTime;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Shared\FirstData\FirstDataConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeTest
 * Add your own group annotations below this line
 */
class FirstDataFacadeTest extends AbstractFirstDataFacadeTest
{
    protected const TRANSACTION_ID = '7777';
    protected const OID = '2bfaf81a-3435';

    /**
     * @var \PyzTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapFirstDataPaymentToQuote(): void
    {
        //Arrange
        $restCheckoutRequestAttributesTransfer = $this->tester->createRestCheckoutRequestAttributesTransferWithSinglePayment(
            static::TRANSACTION_ID,
            static::OID
        );
        $quoteTransfer = $this->tester->createQuoteTransfer();

        //Act
        $mappedQuoteTransfer = $this->tester
            ->getLocator()
            ->firstData()
            ->facade()
            ->mapFirstDataPaymentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        //Assert
        $this->assertNotNull($mappedQuoteTransfer->getPayment());
        $this->assertSame(
            static::TRANSACTION_ID,
            $mappedQuoteTransfer->getPayment()
                ->getFirstDataCreditCard()
                ->getFirstDataTransactionData()
                ->getTransactionId()
        );
    }

    /**
     * @return void
     */
    public function testCheckPaymentAuthorizationTimeOutReturnsTrue(): void
    {
        //Arrange
        $createdAt = (new DateTime())->add(DateInterval::createFromDateString('- 1 day'))->format('Y-m-d H:i:s');
        $orderTransfer = (new OrderTransfer())->setCreatedAt($createdAt);

        //Act
        $result = $this->tester->getFacade()->checkPaymentAuthorizationTimeOut($orderTransfer);

        //Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCheckPaymentAuthorizationTimeOutReturnsFalse(): void
    {
        //Arrange
        $createdAt = (new DateTime())->format('Y-m-d H:i:s');
        $orderTransfer = (new OrderTransfer())->setCreatedAt($createdAt);

        //Act
        $result = $this->tester->getFacade()->checkPaymentAuthorizationTimeOut($orderTransfer);

        //Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testMapFirstDataPaymentToQuoteWithoutPayments(): void
    {
        //Arrange
        $restCheckoutRequestAttributesTransfer = $this->tester->createRestCheckoutRequestAttributesTransferWithSinglePayment(
            static::TRANSACTION_ID,
            static::OID
        );
        $restCheckoutRequestAttributesTransfer->setPayments(new ArrayObject());
        $quoteTransfer = $this->tester->createQuoteTransfer();

        //Act
        $mappedQuoteTransfer = $this->tester
            ->getLocator()
            ->firstData()
            ->facade()
            ->mapFirstDataPaymentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        //Assert
        $this->assertNull($mappedQuoteTransfer->getPayment());
    }

    /**
     * @return void
     */
    public function testLoadPaymentDataByOrderWhenEmptyPayment(): void
    {
        $salesOrderEntity = $this->tester->createTestSalesOrderEntity();
        $this->tester->createTestPaymentFirstDataEntity($salesOrderEntity);
        $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);

        $result = $this->tester->getFacade()->loadPaymentDataByOrder($orderTransfer);

        $this->assertNull($result->getPayments()->getIterator()->current());
    }

    /**
     * @return void
     */
    public function testLoadPaymentDataByOrderWithNoFirstDataPayment(): void
    {
        $salesOrderEntity = $this->tester->createTestSalesOrderEntity();
        $this->tester->createTestPaymentFirstDataEntity($salesOrderEntity);
        $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);
        $orderTransfer->addPayment(
            (new PaymentTransfer())
                ->setPaymentProvider('another provider')
        );

        $result = $this->tester->getFacade()->loadPaymentDataByOrder($orderTransfer);
        $paymentTransfer = $result->getPayments()->getIterator()->current();

        $this->assertNull($paymentTransfer->getFirstDataCreditCard());
    }

    /**
     * @return void
     */
    public function testLoadPaymentDataByOrderWithNoStoredPaymentMethod(): void
    {
        $salesOrderEntity = $this->tester->createTestSalesOrderEntity();
        $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);
        $orderTransfer->addPayment(
            (new PaymentTransfer())
                ->setPaymentProvider(FirstDataConfig::PAYMENT_PROVIDER_NAME_KEY)
        );

        $result = $this->tester->getFacade()->loadPaymentDataByOrder($orderTransfer);
        $paymentTransfer = $result->getPayments()->getIterator()->current();

        $this->assertNull($paymentTransfer->getFirstDataCreditCard());
    }

    /**
     * @return void
     */
    public function testLoadPaymentDataByOrder(): void
    {
        $salesOrderEntity = $this->tester->createTestSalesOrderEntity();
        $this->tester->createTestPaymentFirstDataEntity($salesOrderEntity);
        $orderTransfer = (new OrderTransfer())->fromArray($salesOrderEntity->toArray(), true);
        $orderTransfer->addPayment(
            (new PaymentTransfer())
                ->setPaymentProvider(FirstDataConfig::PAYMENT_PROVIDER_NAME_KEY)
        );

        $result = $this->tester->getFacade()->loadPaymentDataByOrder($orderTransfer);
        /** @var \Generated\Shared\Transfer\PaymentTransfer $payment */
        $payment = $result->getPayments()->getIterator()->current();

        $this->assertNotNull($payment);
        $this->assertEquals($this->tester::OID, $payment->getFirstDataCreditCard()->getFirstDataTransactionData()->getOid());
        $this->assertEquals($this->tester::ID_TRANSACTION, $payment->getFirstDataCreditCard()->getFirstDataTransactionData()->getTransactionId());
    }
}
