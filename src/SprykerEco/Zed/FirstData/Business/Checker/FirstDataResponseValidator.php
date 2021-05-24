<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Checker;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\FirstData\FirstDataClientInterface;
use SprykerEco\Zed\FirstData\FirstDataConfig;

class FirstDataResponseValidator implements FirstDataResponseValidatorInterface
{
    /**
     * @var \SprykerEco\Client\FirstData\FirstDataClientInterface
     */
    protected $firstDataClient;

    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    protected const GLOSSARY_KEY_FIRST_DATA_TRANSACTION_DATA_MISSING = 'first_data.transaction_data_is_missing';
    protected const GLOSSARY_KEY_FIRST_DATA_RESPONSE_HASH_INVALID = 'first_data.response_hash_invalid';

    /**
     * @param \SprykerEco\Client\FirstData\FirstDataClientInterface $firstDataClient
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     */
    public function __construct(
        FirstDataClientInterface $firstDataClient,
        FirstDataConfig $firstDataConfig
    ) {
        $this->firstDataClient = $firstDataClient;
        $this->firstDataConfig = $firstDataConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateFirstDataResponse(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        if ($quoteTransfer->getPayments()->count()) {
            foreach ($quoteTransfer->getPayments() as $selectedPaymentMethod) {
                if ($selectedPaymentMethod->getPaymentMethod() === $this->firstDataConfig::PAYMENT_METHOD_KEY_CREDIT_CARD) {
                    return $this->validateResponseHash($quoteTransfer, $checkoutResponseTransfer);
                }
            }
        }

        if ($quoteTransfer->getPaymentOrFail()->getPaymentMethod() === $this->firstDataConfig::PAYMENT_METHOD_KEY_CREDIT_CARD) {
            return $this->validateResponseHash($quoteTransfer, $checkoutResponseTransfer);
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    protected function validateResponseHash(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $firstDataCreditCard = $quoteTransfer->getPayment() ?
            $quoteTransfer->getPaymentOrFail()->getFirstDataCreditCard() :
            null;
        $firstDataTransactionData = $firstDataCreditCard ? $firstDataCreditCard->getFirstDataTransactionData() : null;

        if (!$firstDataTransactionData) {
            $checkoutErrorTransfer = (new CheckoutErrorTransfer())
                ->setMessage(static::GLOSSARY_KEY_FIRST_DATA_TRANSACTION_DATA_MISSING);

            $checkoutResponseTransfer->addError($checkoutErrorTransfer);
            $checkoutResponseTransfer->setIsSuccess(false);

            return false;
        }

        return true;
    }
}
