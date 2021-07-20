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
        $billingAddress = $firstDataApiRequestTransfer->getBillingAddressOrFail();
        $shippingAddress = $firstDataApiRequestTransfer->getShippingAddressOrFail();

        return [
            'transactionAmount' => [
                'total' => $this->calculateReservationTotal($firstDataApiRequestTransfer),
                'currency' => $firstDataApiRequestTransfer->getCurrencyIsoCode(),
            ],
            'paymentMethod' => [
                'paymentToken' => [
                    'value' => $customerToken->getCardTokenOrFail(),
                    'expiryDate' => [
                        'month' => $customerToken->getExpMonthOrFail(),
                        'year' => $customerToken->getExpYear(),
                    ],
                ],
            ],
            'storeId' => $firstDataApiRequestTransfer->getStoreId(),
            'order' => [
                'orderId' => $firstDataApiRequestTransfer->getOrderOrFail()->getOrderReferenceOrFail(),
                'billing' => [
                    'name' => $billingAddress->getFirstName() ?? '',
                    'customerId' => $billingAddress->getCustomerId() ?? '',
                    'address' => [
                        'company' => $billingAddress->getCompany() ?? '',
                        'address1' => $billingAddress->getAddress1() ?? '',
                        'city' => $billingAddress->getCity() ?? '',
                        'region' => $billingAddress->getRegion() ?? '',
                        'postalCode' => $billingAddress->getZipCode() ?? '',
                        'country' => $billingAddress->getCountry() ?? '',
                    ],
                ],
                'shipping' => [
                    'name' => $shippingAddress->getFirstName() ?? '',
                    'address' => [
                        'address1' => $shippingAddress->getAddress1() ?? '',
                        'city' => $shippingAddress->getCity() ?? '',
                        'region' => $shippingAddress->getRegion() ?? '',
                        'postalCode' => $shippingAddress->getZipCode() ?? '',
                        'country' => $shippingAddress->getCountry() ?? '',
                    ],
                ],
            ],
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
