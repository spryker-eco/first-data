<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Request\Executor;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
use Generated\Shared\Transfer\FirstDataTransactionDataTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\FirstData\FirstDataConfig as SharedFirstDataConfig;
use SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface;
use SprykerEco\Zed\FirstData\FirstDataConfig;

class ReservationRequestExecutor implements FirstDataRequestExecutorInterface
{
    protected const DEFAULT_FUNCTION_METHOD = 'DEBIT';

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface
     */
    protected $firstDataApiClient;

    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @param \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface $firstDataApiClient
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     */
    public function __construct(
        FirstDataApiClientInterface $firstDataApiClient,
        FirstDataConfig $firstDataConfig
    ) {
        $this->firstDataApiClient = $firstDataApiClient;
        $this->firstDataConfig = $firstDataConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executeRequest(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void
    {
        $paymentTransfer = $quoteTransfer->getPayment();

        if (!$paymentTransfer || $paymentTransfer->getPaymentProvider() !== SharedFirstDataConfig::PAYMENT_PROVIDER_NAME_KEY) {
            return;
        }

        $firstDataTransactionData = $paymentTransfer->getFirstDataCreditCardOrFail()->getFirstDataTransactionDataOrFail();

        $firstDataApiResponseTransfer = $this->firstDataApiClient->performApiRequest(
            $this->getFirstDataApiRequestTransfer($quoteTransfer, $firstDataTransactionData)
        );

        if (!$firstDataApiResponseTransfer->getIsSuccess()) {
            $checkoutResponse->setIsSuccess(false);
        }

        $checkoutResponse->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\FirstDataTransactionDataTransfer $firstDataTransactionData
     *
     * @return \Generated\Shared\Transfer\FirstDataApiRequestTransfer
     */
    protected function getFirstDataApiRequestTransfer(
        QuoteTransfer $quoteTransfer,
        FirstDataTransactionDataTransfer $firstDataTransactionData
    ): FirstDataApiRequestTransfer {
        $paymentTokenTransfer = (new FirstDataCustomerTokenTransfer())
            ->setFunction(static::DEFAULT_FUNCTION_METHOD)
            ->setCardToken($firstDataTransactionData->getCardTokenOrFail())
            ->setExpMonth($firstDataTransactionData->getExpMonth())
            ->setExpYear($firstDataTransactionData->getExpYear());

        return (new FirstDataApiRequestTransfer())
            ->setTotals($quoteTransfer->getTotalsOrFail())
            ->setCurrencyIsoCode($quoteTransfer->getCurrencyOrFail()->getCodeOrFail())
            ->setRequestType(FirstDataConfig::FIRST_DATA_RESERVATION_REQUEST_TYPE)
            ->setPaymentMethod(
                (new PaymentMethodTransfer())
                    ->setCustomerToken($paymentTokenTransfer)
            )->setPercentageBuffer($this->firstDataConfig->getAdditionAuthPercentageBuffer())
            ->setStoreId($this->firstDataConfig->getStoreId());
    }
}
