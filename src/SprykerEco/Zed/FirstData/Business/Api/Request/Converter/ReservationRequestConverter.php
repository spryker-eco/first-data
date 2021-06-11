<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Request\Converter;

use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use SprykerEco\Zed\FirstData\FirstDataConfig;

class ReservationRequestConverter implements FirstDataRequestConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): bool
    {
        return $firstDataApiRequestTransfer->getRequestType() === FirstDataConfig::FIRST_DATA_RESERVATION_REQUEST_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return array
     */
    public function convertRequestTransferToArray(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): array
    {
        $customerToken = $firstDataApiRequestTransfer->getPaymentMethodOrFail()->getCustomerTokenOrFail();

        return [
           'transactionAmount' => [
               'total' => $this->calculateReservationTotal($firstDataApiRequestTransfer),
               'currency' => $firstDataApiRequestTransfer->getCurrencyIsoCode(),
           ],
            'paymentMethod' => [
                'paymentToken' => [
                    'function' => $customerToken->getFunctionOrFail(),
                    'value' => $customerToken->getCardTokenOrFail(),
                    'expiryDate' => [
                        'month' => $customerToken->getExpMonthOrFail(),
                        'year' => $customerToken->getExpYear(),
                    ],
                ],
            ],
            'storeId' => $firstDataApiRequestTransfer->getStoreId(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return string
     */
    protected function calculateReservationTotal(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): string
    {
        $grandTotal = $firstDataApiRequestTransfer->getTotalsOrFail()->getGrandTotal();
        $roundedSum = round($grandTotal / 100, 2);

        return (string)$roundedSum;
    }
}
