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
use Spryker\Service\UtilText\UtilTextServiceInterface;
use SprykerEco\Client\FirstData\FirstDataClientInterface;
use SprykerEco\Glue\FirstData\FirstDataConfig;

class FirstDataCreditCardParametersMapper implements FirstDataCreditCardParametersMapperInterface
{
    /**
     * @var \SprykerEco\Glue\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @var \SprykerEco\Client\FirstData\FirstDataClientInterface
     */
    protected $firstDataClient;

    /**
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \SprykerEco\Glue\FirstData\FirstDataConfig $firstDataConfig
     * @param \SprykerEco\Client\FirstData\FirstDataClientInterface $firstDataClient
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct(
        FirstDataConfig $firstDataConfig,
        FirstDataClientInterface $firstDataClient,
        UtilTextServiceInterface $utilTextService
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
        $price = $restCheckoutDataTransfer->getQuote()->getTotals()->getPriceToPay() * (1 + (int)$this->firstDataConfig->getAdditionAuthPercentageBuffer() / 100);

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
        $firstDataHashRequestTransfer->setOid($this->utilTextService->generateRandomString(32));
        $firstDataHashRequestTransfer->setChargeTotal((string)$this->calculateTotal($restCheckoutDataTransfer));
        $firstDataHashRequestTransfer->setCurrency(
            $this->firstDataConfig->getIsoNumberByCode($restCheckoutDataTransfer->getQuote()->getCurrency()->getCode())
        );
        $firstDataHashRequestTransfer->setCustomerId($restCheckoutDataTransfer->getQuote()->getCustomerReference());
        $firstDataHashRequestTransfer->setEmail($restCheckoutDataTransfer->getQuote()->getCustomer()->getEmail());
        $firstDataHashRequestTransfer->setResponseSuccessURL($this->firstDataConfig->getResponseSuccessUrl());
        $firstDataHashRequestTransfer->setResponseFailURL($this->firstDataConfig->getResponseFailUrl());
        $firstDataHashRequestTransfer->setTransactionNotificationURL($this->firstDataConfig->getTransactionNotificationUrl());

        if ($restCheckoutDataTransfer->getQuote()->getBillingAddress()) {
            $fullName = sprintf(
                '%s %s',
                $restCheckoutDataTransfer->getQuote()->getBillingAddress()->getFirstName() ?: '',
                $restCheckoutDataTransfer->getQuote()->getBillingAddress()->getLastName() ?: ''
            );

            $firstDataHashRequestTransfer->setPhone($restCheckoutDataTransfer->getQuote()->getCustomer()->getPhone());
            $firstDataHashRequestTransfer->setBname($fullName);
            $firstDataHashRequestTransfer->setSname($fullName);
            $firstDataHashRequestTransfer->setBaddr1($restCheckoutDataTransfer->getQuote()->getBillingAddress()->getAddress1());
            $firstDataHashRequestTransfer->setBaddr2($restCheckoutDataTransfer->getQuote()->getBillingAddress()->getAddress2());
            $firstDataHashRequestTransfer->setBcity($restCheckoutDataTransfer->getQuote()->getBillingAddress()->getCity());
            $firstDataHashRequestTransfer->setBzip($restCheckoutDataTransfer->getQuote()->getBillingAddress()->getZipCode());
            $firstDataHashRequestTransfer->setBstate($restCheckoutDataTransfer->getQuote()->getBillingAddress()->getRegionIso2Code());
            $firstDataHashRequestTransfer->setBcountry($restCheckoutDataTransfer->getQuote()->getBillingAddress()->getIso2Code());
        }

        if ($restCheckoutDataTransfer->getQuote()->getShippingAddress()) {
            $firstDataHashRequestTransfer->setSaddr1($restCheckoutDataTransfer->getQuote()->getShippingAddress()->getAddress1());
            $firstDataHashRequestTransfer->setSaddr2($restCheckoutDataTransfer->getQuote()->getShippingAddress()->getAddress2());
            $firstDataHashRequestTransfer->setSzip($restCheckoutDataTransfer->getQuote()->getShippingAddress()->getZipCode());
            $firstDataHashRequestTransfer->setScity($restCheckoutDataTransfer->getQuote()->getShippingAddress()->getCity());
            $firstDataHashRequestTransfer->setSstate($restCheckoutDataTransfer->getQuote()->getShippingAddress()->getRegionIso2Code());
            $firstDataHashRequestTransfer->setScountry($restCheckoutDataTransfer->getQuote()->getShippingAddress()->getIso2Code());
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
