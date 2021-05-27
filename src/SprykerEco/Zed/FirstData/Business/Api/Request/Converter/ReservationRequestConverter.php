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
        return [
           'transactionAmount' => [
               'total' => $this->calculateCaptureTotal($firstDataApiRequestTransfer),
               'currency' => $firstDataApiRequestTransfer
                   ->getOrderOrFail()
                   ->getCurrencyIsoCode(),
           ],
            'paymentMethod' => [
                'paymentToken' => [
                    'function' => $firstDataApiRequestTransfer
                        ->getPaymentMethodOrFail()
                        ->getPaymentTokenOrFail()
                        ->getFunction(),
                    'value' => $firstDataApiRequestTransfer
                        ->getPaymentMethodOrFail()
                        ->getPaymentTokenOrFail()
                        ->getValueOrFail(),
                ],
            ],
            'storeId' => $firstDataApiRequestTransfer->getStoreNameOrFail(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return string
     */
    protected function calculateCaptureTotal(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): string
    {
        $total = 0;
        foreach ($firstDataApiRequestTransfer->getOrderOrFail()->getItems() as $item) {
            if (in_array($item->getIdSalesOrderItem(), $firstDataApiRequestTransfer->getOrderItemIds())) {
                $total += (int)$item->getSumPriceToPayAggregation();
            }
        }

        $total = $total * (1 + (int)$firstDataApiRequestTransfer->getPercentageBuffer() / 100);

        return (string)(round($total / 100, 2));
    }
}
