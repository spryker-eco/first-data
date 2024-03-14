<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\FirstData\Business;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\FirstDataTransactionDataTransfer;
use Generated\Shared\Transfer\FirstDataTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerEcoTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeReservationTest
 */
class FirstDataFacadeReservationTest extends AbstractFirstDataFacadeTest
{
    protected const PAYMENT_PROVIDER_NAME_KEY = 'firstData';
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerEcoTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteReservationOmsCommandMustReturnSuccessResponseAfterReservationRequest(): void
    {
        //Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $clientResponse = $this->tester->getClientResponse();
        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider(static::PAYMENT_PROVIDER_NAME_KEY)
            ->setFirstDataCreditCard(
                (new FirstDataTransfer())->setFirstDataTransactionData(
                    ( new FirstDataTransactionDataTransfer())
                        ->setCardToken('21EEEE8A-398C-4D9A-B5D6-F1A6273F933F')
                        ->setExpYear(02)
                        ->setExpMonth(21)
                )
            );
        $currency = (new CurrencyTransfer())
            ->setName($clientResponse['approvedAmount']['currency'])
            ->setCode($clientResponse['approvedAmount']['currency']);

        $totals = (new TotalsTransfer())->setGrandTotal($clientResponse['approvedAmount']['total']);

        $quoteTransfer = $this->tester->createQuoteTransfer()
            ->setShippingAddress(new AddressTransfer())
            ->setBillingAddress(new AddressTransfer())
            ->setPayment($paymentTransfer)
            ->setTotals($totals)
            ->setCurrency($currency);

        $checkoutResponse = (new CheckoutResponseTransfer())->setSaveOrder($saveOrderTransfer);

        //Act
        $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock($clientResponse))
            ->executeReservationRequest($quoteTransfer, $checkoutResponse);

        //Assert
        $this->assertTrue($checkoutResponse->getIsSuccess());
    }
}
