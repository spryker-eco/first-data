<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Request\Executor;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTokenTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\FirstData\FirstDataConfig as SharedFirstDataConfig;
use SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface;
use SprykerEco\Zed\FirstData\FirstDataConfig;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface;

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
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface $firstDataApiClient
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface $entityManager
     */
    public function __construct(
        FirstDataApiClientInterface $firstDataApiClient,
        FirstDataConfig $firstDataConfig,
        FirstDataEntityManagerInterface $entityManager
    ) {
        $this->firstDataApiClient = $firstDataApiClient;
        $this->firstDataConfig = $firstDataConfig;
        $this->entityManager = $entityManager;
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
        $cardToken = $firstDataTransactionData->getCardToken();

        $paymentTokenTransfer = (new PaymentTokenTransfer())
            ->setFunction(static::DEFAULT_FUNCTION_METHOD)
            ->setValue($cardToken);

        $firstDataApiRequestTransfer = (new FirstDataApiRequestTransfer())
            ->setTotals($quoteTransfer->getTotalsOrFail())
            ->setCurrencyIsoCode($quoteTransfer->getCurrencyOrFail()->getCodeOrFail())
            ->setRequestType(FirstDataConfig::FIRST_DATA_RESERVATION_REQUEST_TYPE)
            ->setPaymentMethod(
                (new PaymentMethodTransfer())->setPaymentToken($paymentTokenTransfer)
            )->setPercentageBuffer($this->firstDataConfig->getAdditionAuthPercentageBuffer())
            ->setStoreId($this->firstDataConfig->getStoreId());

        $firstDataApiResponseTransfer = $this->firstDataApiClient->performApiRequest($firstDataApiRequestTransfer);

        if (!$firstDataApiResponseTransfer->getIsSuccess()) {
            $checkoutResponse->setIsSuccess(false);
        }

        $this->entityManager->saveCardToken($quoteTransfer->getCustomerOrFail(), $cardToken);
    }
}
