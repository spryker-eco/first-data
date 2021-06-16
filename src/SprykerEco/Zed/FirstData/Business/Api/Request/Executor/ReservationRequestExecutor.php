<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Request\Executor;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
use Generated\Shared\Transfer\FirstDataTransactionDataTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\FirstData\FirstDataConfig as SharedFirstDataConfig;
use SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface;
use SprykerEco\Zed\FirstData\Business\Saver\FirstDataOrderPaymentSaverInterface;
use SprykerEco\Zed\FirstData\FirstDataConfig;

class ReservationRequestExecutor implements FirstDataRequestExecutorInterface
{
    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface
     */
    protected $firstDataApiClient;

    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Saver\FirstDataOrderPaymentSaverInterface
     */
    protected $firstDataOrderPaymentSaver;

    /**
     * @param \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface $firstDataApiClient
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     * @param \SprykerEco\Zed\FirstData\Business\Saver\FirstDataOrderPaymentSaverInterface $firstDataOrderPaymentSaver
     */
    public function __construct(
        FirstDataApiClientInterface $firstDataApiClient,
        FirstDataConfig $firstDataConfig,
        FirstDataOrderPaymentSaverInterface $firstDataOrderPaymentSaver
    ) {
        $this->firstDataApiClient = $firstDataApiClient;
        $this->firstDataConfig = $firstDataConfig;
        $this->firstDataOrderPaymentSaver = $firstDataOrderPaymentSaver;
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
            $this->getFirstDataApiRequestTransfer($quoteTransfer, $firstDataTransactionData, $checkoutResponse)
        );

        if (!$firstDataApiResponseTransfer->getIsSuccess()) {
            $checkoutResponse->setIsSuccess(false);
        }

        $quoteTransfer->getPaymentOrFail()->getFirstDataCreditCardOrFail()->setFirstDataTransactionData(
            $this->getFirstDataTransactionDataWithResponseData($quoteTransfer, $firstDataApiResponseTransfer)
        );

        $this->firstDataOrderPaymentSaver->savePaymentEntities($quoteTransfer, $checkoutResponse->getSaveOrderOrFail());

        $checkoutResponse->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\FirstDataTransactionDataTransfer $firstDataTransactionData
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\FirstDataApiRequestTransfer
     */
    protected function getFirstDataApiRequestTransfer(
        QuoteTransfer $quoteTransfer,
        FirstDataTransactionDataTransfer $firstDataTransactionData,
        CheckoutResponseTransfer $checkoutResponse
    ): FirstDataApiRequestTransfer {
        $paymentTokenTransfer = (new FirstDataCustomerTokenTransfer())
            ->setCardToken($firstDataTransactionData->getCardTokenOrFail())
            ->setExpMonth($firstDataTransactionData->getExpMonth())
            ->setExpYear($firstDataTransactionData->getExpYear());

        return (new FirstDataApiRequestTransfer())
            ->setTotals($quoteTransfer->getTotalsOrFail())
            ->setCurrencyIsoCode($quoteTransfer->getCurrencyOrFail()->getCodeOrFail())
            ->setBillingAddress($quoteTransfer->getBillingAddressOrFail())
            ->setShippingAddress($quoteTransfer->getShippingAddressOrFail())
            ->setRequestType(FirstDataConfig::FIRST_DATA_RESERVATION_REQUEST_TYPE)
            ->setOrder(
                (new OrderTransfer())
                    ->setOrderReference($checkoutResponse->getSaveOrderOrFail()->getOrderReferenceOrFail())
            )
            ->setPaymentMethod(
                (new PaymentMethodTransfer())
                    ->setCustomerToken($paymentTokenTransfer)
            )->setPercentageBuffer($this->firstDataConfig->getAdditionAuthPercentageBuffer())
            ->setStoreId($this->firstDataConfig->getStoreId());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\FirstDataApiResponseTransfer $firstDataApiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataTransactionDataTransfer
     */
    protected function getFirstDataTransactionDataWithResponseData(
        QuoteTransfer $quoteTransfer,
        FirstDataApiResponseTransfer $firstDataApiResponseTransfer
    ): FirstDataTransactionDataTransfer {
        $firstDataTransactionDataTransfer = $quoteTransfer
            ->getPaymentOrFail()
            ->getFirstDataCreditCardOrFail()
            ->getFirstDataTransactionDataOrFail();

        return $firstDataTransactionDataTransfer->setTransactionId(
            $firstDataApiResponseTransfer->getClientResponseOrFail()->getIpgTransactionId()
        )
            ->setOid($firstDataApiResponseTransfer->getClientResponseOrFail()->getOrderId());
    }
}
