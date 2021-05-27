<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Logger;

use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataHttpRequestTransfer;

interface FirstDataApiLoggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstDataHttpRequestTransfer $firstDataHttpRequestTransfer
     * @param \Generated\Shared\Transfer\FirstDataApiResponseTransfer $firstDataApiResponseTransfer
     * @param string|null $requestType
     *
     * @return void
     */
    public function logApiCall(
        FirstDataHttpRequestTransfer $firstDataHttpRequestTransfer,
        FirstDataApiResponseTransfer $firstDataApiResponseTransfer,
        ?string $requestType
    ): void;
}
