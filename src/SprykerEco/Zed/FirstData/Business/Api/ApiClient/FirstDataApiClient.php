<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\ApiClient;

use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use SprykerEco\Zed\FirstData\Business\Api\Logger\FirstDataApiLoggerInterface;
use SprykerEco\Zed\FirstData\Business\Api\Request\Builder\FirstDataRequestBuilderInterface;
use SprykerEco\Zed\FirstData\Business\Api\Response\Converter\FirstDataResponseConverterInterface;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Exception\FirstDataGuzzleRequestException;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapterInterface;
use SprykerEco\Zed\FirstData\FirstDataConfig;

class FirstDataApiClient implements FirstDataApiClientInterface
{
    /**
     * @var \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapterInterface
     */
    protected $guzzleHttpClientAdapter;

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\Request\Builder\FirstDataRequestBuilderInterface
     */
    protected $firstDataRequestBuilder;

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\Response\Converter\FirstDataResponseConverterInterface
     */
    protected $firstDataResponseConverter;

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\Logger\FirstDataApiLoggerInterface
     */
    protected $firstDataApiLogger;

    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @param \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapterInterface $guzzleHttpClientAdapter
     * @param \SprykerEco\Zed\FirstData\Business\Api\Request\Builder\FirstDataRequestBuilderInterface $firstDataRequestBuilder
     * @param \SprykerEco\Zed\FirstData\Business\Api\Response\Converter\FirstDataResponseConverterInterface $firstDataResponseConverter
     * @param \SprykerEco\Zed\FirstData\Business\Api\Logger\FirstDataApiLoggerInterface $firstDataApiLogger
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     */
    public function __construct(
        FirstDataGuzzleHttpClientAdapterInterface $guzzleHttpClientAdapter,
        FirstDataRequestBuilderInterface $firstDataRequestBuilder,
        FirstDataResponseConverterInterface $firstDataResponseConverter,
        FirstDataApiLoggerInterface $firstDataApiLogger,
        FirstDataConfig $firstDataConfig
    ) {
        $this->guzzleHttpClientAdapter = $guzzleHttpClientAdapter;
        $this->firstDataRequestBuilder = $firstDataRequestBuilder;
        $this->firstDataResponseConverter = $firstDataResponseConverter;
        $this->firstDataApiLogger = $firstDataApiLogger;
        $this->firstDataConfig = $firstDataConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function performApiRequest(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): FirstDataApiResponseTransfer
    {
        $isSuccess = true;
        $firstDataHttpRequestTransfer = $this->firstDataRequestBuilder->buildRequest($firstDataApiRequestTransfer);
        $requestUrl = $firstDataHttpRequestTransfer->getRequestUrl() ?? $this->getRequestUrl($firstDataApiRequestTransfer);

        try {
            $guzzleHttpResponse = $this->guzzleHttpClientAdapter->post(
                $requestUrl,
                $firstDataHttpRequestTransfer->getHeaders(),
                $firstDataHttpRequestTransfer->getBodyOrFail()
            );
        } catch (FirstDataGuzzleRequestException $requestException) {
            $isSuccess = false;
            $guzzleHttpResponse = $requestException->getResponse();
        }

        $firstDataApiResponseTransfer = $this->firstDataResponseConverter->convertToResponseTransfer($guzzleHttpResponse, $isSuccess);

        $this->firstDataApiLogger
            ->logApiCall(
                $firstDataHttpRequestTransfer,
                $firstDataApiResponseTransfer,
                $firstDataApiRequestTransfer->getRequestType()
            );

        return $firstDataApiResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return string
     */
    protected function getRequestUrl(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): string
    {
        return sprintf(
            '%s/%s',
            $this->firstDataConfig->getApiEndpoint($firstDataApiRequestTransfer->getRequestTypeOrFail()),
            $firstDataApiRequestTransfer->getTransactionId()
        );
    }
}
