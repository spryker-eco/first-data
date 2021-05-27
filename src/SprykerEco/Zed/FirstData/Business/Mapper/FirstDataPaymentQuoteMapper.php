<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Mapper;

use Generated\Shared\Transfer\FirstDataTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentTransfer;

class FirstDataPaymentQuoteMapper implements FirstDataPaymentQuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapFirstDataPaymentToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $restPaymentTransfers = $restCheckoutRequestAttributesTransfer->getPayments();

        if (!$restPaymentTransfers->count()) {
            return $quoteTransfer;
        }

        $paymentTransfer = $this->createPaymentTransfer(
            $restPaymentTransfers->getIterator()->current(),
            $restCheckoutRequestAttributesTransfer
        );

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestPaymentTransfer $restPaymentTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createPaymentTransfer(
        RestPaymentTransfer $restPaymentTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): PaymentTransfer {
        $paymentTransfer = (new PaymentTransfer())->fromArray($restPaymentTransfer->toArray(), true);
        $firstDataTransfer = (new FirstDataTransfer())
            ->setFirstDataTransactionData($restCheckoutRequestAttributesTransfer->getFirstDataTransactionData());

        $paymentTransfer->setPaymentProvider($restPaymentTransfer->getPaymentProviderName())
            ->setPaymentMethod($restPaymentTransfer->getPaymentMethodName())
            ->setFirstDataCreditCard($firstDataTransfer);

        return $paymentTransfer;
    }
}
