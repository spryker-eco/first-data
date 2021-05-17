<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Request\Builder;

use ArrayObject;
use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataHttpRequestTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerEco\Zed\FirstData\Business\Api\Generator\HashGeneratorInterface;
use SprykerEco\Zed\FirstData\FirstDataConfig;

class FirstDataRequestBuilder implements FirstDataRequestBuilderInterface
{
    protected const REQUEST_KEY_REQUEST_TYPE = 'requestType';

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\Request\Converter\FirstDataRequestConverterInterface[]
     */
    protected $firstDataRequestConverters;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
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
     * @param \SprykerEco\Zed\FirstData\Business\Api\Request\Converter\FirstDataRequestConverterInterface[] $firstDataRequestConverters
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     * @param \SprykerEco\Zed\FirstData\Business\Api\Generator\HashGeneratorInterface $hashGenerator
     */
    public function __construct(
        array $firstDataRequestConverters,
        UtilEncodingServiceInterface $utilEncodingService,
        FirstDataConfig $firstDataConfig,
        HashGeneratorInterface $hashGenerator
    ) {
        $this->firstDataRequestConverters = $firstDataRequestConverters;
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
        $requestPayload = $this->getRequestPayload($firstDataApiRequestTransfer);

        return (new FirstDataHttpRequestTransfer())
            ->setBody($requestPayload)
            ->setHeaders($this->buildRequestHeaders($requestPayload));
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return string
     */
    protected function getRequestPayload(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): string
    {
        $requestPayload = [];
        $requestPayload = $this->executeFirstDataRequestConverter($requestPayload, $firstDataApiRequestTransfer);
        $requestPayload[static::REQUEST_KEY_REQUEST_TYPE] = $firstDataApiRequestTransfer->getRequestType();

        return $this->utilEncodingService->encodeJson($requestPayload);
    }

    /**
     * @param array $requestPayload
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return array
     */
    protected function executeFirstDataRequestConverter(array $requestPayload, FirstDataApiRequestTransfer $firstDataApiRequestTransfer): array
    {
        foreach ($this->firstDataRequestConverters as $firstDataRequestConverter) {
            if ($firstDataRequestConverter->isApplicable($firstDataApiRequestTransfer)) {
                $requestPayload = $firstDataRequestConverter->convertRequestTransferToArray($firstDataApiRequestTransfer);
                $requestPayload = $this->removeRedundantParams($requestPayload);
                break;
            }
        }

        return $requestPayload;
    }

    /**
     * @param string $requestPayload
     *
     * @return array
     */
    protected function buildRequestHeaders(string $requestPayload): array
    {
        $clientRequestId = Uuid::uuid4()->toString();
        $timestamp = time() * 1000;

        return [
            'Client-Request-Id' => $clientRequestId,
            'Api-Key' => $this->firstDataConfig->getFirstDataApiKey(),
            'Timestamp' => $timestamp,
            'Message-Signature' => $this->hashGenerator->generateHash([
                $this->firstDataConfig->getFirstDataApiKey(),
                $clientRequestId,
                $timestamp,
                $requestPayload,
            ]),
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function removeRedundantParams(array $data): array
    {
        $data = array_filter($data, function ($item) {
            if ($item instanceof ArrayObject) {
                return $item->count() !== 0;
            }

            return $item !== null;
        });

        foreach ($data as $key => $value) {
            if (is_array($value) || $value instanceof ArrayObject) {
                $data[$key] = $this->removeRedundantParams((array)$value);
            }
        }

        return $data;
    }
}
