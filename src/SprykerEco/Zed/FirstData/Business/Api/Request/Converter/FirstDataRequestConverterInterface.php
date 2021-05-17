<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Request\Converter;

use Generated\Shared\Transfer\FirstDataApiRequestTransfer;

interface FirstDataRequestConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return array
     */
    public function convertRequestTransferToArray(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): bool;
}
