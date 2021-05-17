<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use SprykerEco\Glue\FirstData\FirstDataConfig;
use SprykerEco\Shared\FirstData\FirstDataConfig as SharedFirstDataConfig;
use Symfony\Component\HttpFoundation\Response;

class FirstDataPaymentValidator implements FirstDataPaymentValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validate(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer
    {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();

        if (!$restCheckoutRequestAttributesTransfer->getPayments()->count()) {
            return $restErrorCollectionTransfer;
        }

        /** @var \Generated\Shared\Transfer\RestPaymentTransfer $restPaymentTransfer */
        $restPaymentTransfer = $restCheckoutRequestAttributesTransfer->getPayments()->offsetGet(0);

        if ($restPaymentTransfer->getPaymentProviderName() !== SharedFirstDataConfig::PAYMENT_PROVIDER_NAME_KEY) {
            return $restErrorCollectionTransfer;
        }

        $this->validateOid($restCheckoutRequestAttributesTransfer, $restErrorCollectionTransfer);
        $this->validateTransactionId($restCheckoutRequestAttributesTransfer, $restErrorCollectionTransfer);

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function validateOid(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestErrorCollectionTransfer $restErrorCollectionTransfer
    ): RestErrorCollectionTransfer {
        $firstDataTransactionDataTransfer = $restCheckoutRequestAttributesTransfer->getFirstDataTransactionData();

        if (!$firstDataTransactionDataTransfer || !$firstDataTransactionDataTransfer->getOid()) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setCode(FirstDataConfig::RESPONSE_CODE_INVALID_FIRST_DATA_PAYMENT_OID)
                ->setDetail(FirstDataConfig::RESPONSE_DETAILS_FIRST_DATA_PAYMENT_OID_MISSING);
            $restErrorCollectionTransfer->addRestError($restErrorMessageTransfer);
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function validateTransactionId(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestErrorCollectionTransfer $restErrorCollectionTransfer
    ): RestErrorCollectionTransfer {
        $firstDataTransactionDataTransfer = $restCheckoutRequestAttributesTransfer->getFirstDataTransactionData();

        if (!$firstDataTransactionDataTransfer || !$firstDataTransactionDataTransfer->getTransactionId()) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setCode(FirstDataConfig::RESPONSE_CODE_INVALID_FIRST_DATA_PAYMENT_TRANSACTION_ID)
                ->setDetail(FirstDataConfig::RESPONSE_DETAILS_FIRST_DATA_PAYMENT_TRANSACTION_ID_MISSING);
            $restErrorCollectionTransfer->addRestError($restErrorMessageTransfer);
        }

        return $restErrorCollectionTransfer;
    }
}
