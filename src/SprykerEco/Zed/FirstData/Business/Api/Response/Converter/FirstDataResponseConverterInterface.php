<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Response\Converter;

use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface;

interface FirstDataResponseConverterInterface
{
    /**
     * @param \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface $guzzleResponse
     * @param bool $isSuccess
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function convertToResponseTransfer(
        FirstDataGuzzleResponseInterface $guzzleResponse,
        bool $isSuccess
    ): FirstDataApiResponseTransfer;
}
