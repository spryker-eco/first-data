<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Request\Builder;

use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataHttpRequestTransfer;
use Ramsey\Uuid\Uuid;
use SprykerEco\Zed\FirstData\Business\Api\Generator\HashGeneratorInterface;
use SprykerEco\Zed\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface;
use SprykerEco\Zed\FirstData\FirstDataConfig;

class FirstDataAuthorizeSessionRequestBuilder implements FirstDataRequestBuilderInterface
{
    protected const DEFAULT_AUTHORIZATION_REQUEST_CONTENT_TYPE = 'application/json';

    /**
     * @var \SprykerEco\Zed\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\Generator\HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @param \SprykerEco\Zed\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface $utilEncodingService
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     * @param \SprykerEco\Zed\FirstData\Business\Api\Generator\HashGeneratorInterface $hashGenerator
     */
    public function __construct(
        FirstDataToUtilEncodingServiceInterface $utilEncodingService,
        FirstDataConfig $firstDataConfig,
        HashGeneratorInterface $hashGenerator
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->firstDataConfig = $firstDataConfig;
        $this->hashGenerator = $hashGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataHttpRequestTransfer
     */
    public function buildRequest(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): FirstDataHttpRequestTransfer
    {
        $requestPayload = $this->getRequestPayload();

        return (new FirstDataHttpRequestTransfer())
            ->setBody($requestPayload)
            ->setRequestUrl($this->firstDataConfig->geAuthorizeSessionApiEndpoint())
            ->setHeaders($this->buildRequestHeaders($requestPayload));
    }

    /**
     * @return string
     */
    protected function getRequestPayload(): string
    {
        $requestPayload = [
            'gateway' => $this->firstDataConfig->getFirstDataGatewayProviderName(),
            'apiKey' => $this->firstDataConfig->getFirstDataApiKey(),
            'apiSecret' => $this->firstDataConfig->getFirstDataApiSecret(),
            'storeId' => $this->firstDataConfig->getStoreId(),
            'zeroDollarAuth' => false,
        ];

        return $this->utilEncodingService->encodeJson($requestPayload) ?? '';
    }

    /**
     * @param string $requestPayload
     *
     * @return array
     */
    protected function buildRequestHeaders(string $requestPayload): array
    {
        $timestamp = time() * 1000;
        $nonce = Uuid::uuid4()->toString();
        $apiKey = $this->firstDataConfig->getFirstDataApiKey();

        return [
            'Api-Key' => $apiKey,
            'Content-Type' => static::DEFAULT_AUTHORIZATION_REQUEST_CONTENT_TYPE,
            'Timestamp' => $timestamp,
            'Nonce' => $nonce,
            'Content-Length' => strlen($requestPayload),
            'Message-Signature' => $this->hashGenerator->generateHash([
                $apiKey,
                $nonce,
                $timestamp,
                $requestPayload,
            ]),
        ];
    }
}
