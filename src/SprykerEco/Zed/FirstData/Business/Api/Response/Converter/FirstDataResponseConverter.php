<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Response\Converter;

use Generated\Shared\Transfer\FirstDataApiClientResponseTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface;

class FirstDataResponseConverter implements FirstDataResponseConverterInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(UtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface $guzzleResponse
     * @param bool $isSuccess
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function convertToResponseTransfer(
        FirstDataGuzzleResponseInterface $guzzleResponse,
        bool $isSuccess
    ): FirstDataApiResponseTransfer {
        $responseData = $this->utilEncodingService->decodeJson($guzzleResponse->getResponseBody(), true) ?? [];

        $firstDataApiResponseTransfer = (new FirstDataApiResponseTransfer());
        $firstDataApiResponseTransfer->setIsSuccess($isSuccess);

        if (!$isSuccess) {
            return $firstDataApiResponseTransfer->fromArray($responseData, true);
        }

        $firstDataApiClientResponseTransfer = new FirstDataApiClientResponseTransfer();
        $firstDataApiClientResponseTransfer->fromArray($responseData, true);
        $firstDataApiResponseTransfer->setClientResponse($firstDataApiClientResponseTransfer);

        return $firstDataApiResponseTransfer;
    }
}
