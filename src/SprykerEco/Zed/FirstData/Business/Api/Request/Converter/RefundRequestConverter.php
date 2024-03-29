<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Request\Converter;

use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use SprykerEco\Zed\FirstData\FirstDataConfig;

class RefundRequestConverter implements FirstDataRequestConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): bool
    {
        return $firstDataApiRequestTransfer->getRequestType() === FirstDataConfig::FIRST_DATA_REFUND_REQUEST_TYPE;
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
                'total' => $this->calculateRefundTotal($firstDataApiRequestTransfer),
                'currency' => $firstDataApiRequestTransfer
                    ->getOrderOrFail()
                    ->getCurrencyIsoCode(),
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return string
     */
    protected function calculateRefundTotal(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): string
    {
        $total = 0;
        foreach ($firstDataApiRequestTransfer->getRefundOrFail()->getItems() as $item) {
            $total += (int)$item->getRefundableAmount();
        }

        return (string)(round($total / 100, 2));
    }
}
