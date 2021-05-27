<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Processor\Mapper;

use DateTime;
use Generated\Shared\Transfer\FirstDataCreditCardParametersTransfer;
use Generated\Shared\Transfer\FirstDataHashRequestTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use SprykerEco\Client\FirstData\FirstDataClientInterface;
use SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilTextServiceInterface;
use SprykerEco\Glue\FirstData\FirstDataConfig;

class FirstDataCreditCardParametersMapper implements FirstDataCreditCardParametersMapperInterface
{
    protected const DEFAULT_OID_LENGTH = 32;

    /**
     * @var \SprykerEco\Glue\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @var \SprykerEco\Client\FirstData\FirstDataClientInterface
     */
    protected $firstDataClient;

    /**
     * @var \SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \SprykerEco\Glue\FirstData\FirstDataConfig $firstDataConfig
     * @param \SprykerEco\Client\FirstData\FirstDataClientInterface $firstDataClient
     * @param \SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        FirstDataConfig $firstDataConfig,
        FirstDataClientInterface $firstDataClient,
        FirstDataToUtilTextServiceInterface $utilTextService
    ) {
        $this->firstDataConfig = $firstDataConfig;
        $this->firstDataClient = $firstDataClient;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapFirstDataCreditCardParametersTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer,
        RestCheckoutDataTransfer $restCheckoutDataTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        if (!$this->isCreditCardPaymentMethodSelected($restCheckoutDataResponseAttributesTransfer)) {
            return $restCheckoutDataResponseAttributesTransfer;
        }

        $restCheckoutDataResponseAttributesTransfer->setFirstDataPaymentProcessingLink(
            $this->firstDataConfig->getFirstDataPaymentProcessingLink()
        );

        return $restCheckoutDataResponseAttributesTransfer->setFirstDataCreditCardParameters(
            $this->createFirstDataCreditCardParametersTransfer(
                $this->createFirstDataHashRequestTransfer($restCheckoutDataTransfer)
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return bool
     */
    protected function isCreditCardPaymentMethodSelected(
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): bool {
        foreach ($restCheckoutDataResponseAttributesTransfer->getSelectedPaymentMethods() as $selectedPaymentMethod) {
            if ($selectedPaymentMethod->getPaymentMethodName() === $this->firstDataConfig->getMethodNameCreditCard()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return float
     */
    protected function calculateTotal(RestCheckoutDataTransfer $restCheckoutDataTransfer): float
    {
        $price = $restCheckoutDataTransfer->getQuoteOrFail()->getTotalsOrFail()->getPriceToPay() * (1 + (int)$this->firstDataConfig->getAdditionAuthPercentageBuffer() / 100);

        return (float)($price / 100);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataHashRequestTransfer
     */
    protected function createFirstDataHashRequestTransfer(RestCheckoutDataTransfer $restCheckoutDataTransfer): FirstDataHashRequestTransfer
    {
        $firstDataHashRequestTransfer = new FirstDataHashRequestTransfer();
        $firstDataHashRequestTransfer->setMobileMode($this->firstDataConfig->getMobileMode());
        $firstDataHashRequestTransfer->setCheckoutOption($this->firstDataConfig->getCheckoutOption());
        $firstDataHashRequestTransfer->setLanguage($this->firstDataConfig->getLanguage());
        $firstDataHashRequestTransfer->setAssignToken($this->firstDataConfig->getAssignToken());
        $firstDataHashRequestTransfer->setTokenType($this->firstDataConfig->getTokenType());
        $firstDataHashRequestTransfer->setDeclineHostedDataDuplicates($this->firstDataConfig->getDeclineHostedDataDuplicates());
        $firstDataHashRequestTransfer->setTxnType($this->firstDataConfig->getTransactionType());
        $firstDataHashRequestTransfer->setTimezone($this->firstDataConfig->getTimezone());
        $firstDataHashRequestTransfer->setTxnDateTime((new DateTime())->format($this->firstDataConfig->getDateTimeFormat()));
        $firstDataHashRequestTransfer->setHashAlgorithm($this->firstDataConfig->getHmacHashAlgo());
        $firstDataHashRequestTransfer->setStoreName($this->firstDataConfig->getStoreName());
        $firstDataHashRequestTransfer->setAuthenticateTransaction($this->firstDataConfig->getIs3dSecure());
        $firstDataHashRequestTransfer->setOid($this->utilTextService->generateRandomString(static::DEFAULT_OID_LENGTH));
        $firstDataHashRequestTransfer->setChargeTotal((string)$this->calculateTotal($restCheckoutDataTransfer));
        $firstDataHashRequestTransfer->setCurrency(
            $this->firstDataConfig->getIsoNumberByCode($restCheckoutDataTransfer->getQuoteOrFail()->getCurrencyOrFail()->getCodeOrFail())
        );
        $firstDataHashRequestTransfer->setCustomerId($restCheckoutDataTransfer->getQuoteOrFail()->getCustomerReference());
        $firstDataHashRequestTransfer->setEmail($restCheckoutDataTransfer->getQuoteOrFail()->getCustomerOrFail()->getEmail());
        $firstDataHashRequestTransfer->setResponseSuccessURL($this->firstDataConfig->getResponseSuccessUrl());
        $firstDataHashRequestTransfer->setResponseFailURL($this->firstDataConfig->getResponseFailUrl());
        $firstDataHashRequestTransfer->setTransactionNotificationURL($this->firstDataConfig->getTransactionNotificationUrl());

        if ($restCheckoutDataTransfer->getQuoteOrFail()->getBillingAddress()) {
            $fullName = sprintf(
                '%s %s',
                $restCheckoutDataTransfer->getQuoteOrFail()->getBillingAddressOrFail()->getFirstName() ?: '',
                $restCheckoutDataTransfer->getQuoteOrFail()->getBillingAddressOrFail()->getLastName() ?: ''
            );

            $firstDataHashRequestTransfer->setPhone($restCheckoutDataTransfer->getQuoteOrFail()->getCustomerOrFail()->getPhone());
            $firstDataHashRequestTransfer->setBname($fullName);
            $firstDataHashRequestTransfer->setSname($fullName);
            $firstDataHashRequestTransfer->setBaddr1($restCheckoutDataTransfer->getQuoteOrFail()->getBillingAddressOrFail()->getAddress1());
            $firstDataHashRequestTransfer->setBaddr2($restCheckoutDataTransfer->getQuoteOrFail()->getBillingAddressOrFail()->getAddress2());
            $firstDataHashRequestTransfer->setBcity($restCheckoutDataTransfer->getQuoteOrFail()->getBillingAddressOrFail()->getCity());
            $firstDataHashRequestTransfer->setBzip($restCheckoutDataTransfer->getQuoteOrFail()->getBillingAddressOrFail()->getZipCode());
            $firstDataHashRequestTransfer->setBcountry($restCheckoutDataTransfer->getQuoteOrFail()->getBillingAddressOrFail()->getIso2Code());
        }

        if ($restCheckoutDataTransfer->getQuoteOrFail()->getShippingAddress()) {
            $firstDataHashRequestTransfer->setSaddr1($restCheckoutDataTransfer->getQuoteOrFail()->getShippingAddressOrFail()->getAddress1());
            $firstDataHashRequestTransfer->setSaddr2($restCheckoutDataTransfer->getQuoteOrFail()->getShippingAddressOrFail()->getAddress2());
            $firstDataHashRequestTransfer->setSzip($restCheckoutDataTransfer->getQuoteOrFail()->getShippingAddressOrFail()->getZipCode());
            $firstDataHashRequestTransfer->setScity($restCheckoutDataTransfer->getQuoteOrFail()->getShippingAddressOrFail()->getCity());
            $firstDataHashRequestTransfer->setScountry($restCheckoutDataTransfer->getQuoteOrFail()->getShippingAddressOrFail()->getIso2Code());
        }

        return $firstDataHashRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataHashRequestTransfer $firstDataHashRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCreditCardParametersTransfer
     */
    protected function createFirstDataCreditCardParametersTransfer(
        FirstDataHashRequestTransfer $firstDataHashRequestTransfer
    ): FirstDataCreditCardParametersTransfer {
        $firstDataCreditCardParametersTransfer = (new FirstDataCreditCardParametersTransfer())
            ->fromArray($firstDataHashRequestTransfer->toArray(), true);

        return $firstDataCreditCardParametersTransfer->setHashExtended(
            $this->firstDataClient->generateHash($firstDataHashRequestTransfer)
        );
    }
}
