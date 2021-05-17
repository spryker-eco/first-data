<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Dependency\External\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Exception\FirstDataGuzzleRequestException;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponse;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface;

class FirstDataGuzzleHttpClientAdapter implements FirstDataGuzzleHttpClientAdapterInterface
{
    protected const DEFAULT_TIMEOUT = 45;
    protected const HEADER_CONTENT_TYPE_KEY = 'Content-Type';
    protected const HEADER_CONTENT_TYPE_VALUE = 'application/json';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzleHttpClient;

    public function __construct()
    {
        $this->guzzleHttpClient = new Client([
            RequestOptions::TIMEOUT => static::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @param string $url
     * @param array $headers
     * @param string $body
     *
     * @throws \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Exception\FirstDataGuzzleRequestException
     *
     * @return \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface
     */
    public function post(string $url, array $headers = [], string $body = ''): FirstDataGuzzleResponseInterface
    {
        try {
            $headers[static::HEADER_CONTENT_TYPE_KEY] = static::HEADER_CONTENT_TYPE_VALUE;
            $options = [
                RequestOptions::BODY => $body,
                RequestOptions::HEADERS => $headers,
            ];
            $response = $this->guzzleHttpClient->post($url, $options);
        } catch (RequestException $requestException) {
            throw new FirstDataGuzzleRequestException(
                $this->createFirstDataGuzzleResponse($requestException->getResponse()),
                $requestException->getMessage(),
                $requestException->getCode(),
                $requestException
            );
        }

        return $this->createFirstDataGuzzleResponse($response);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface|null $response
     *
     * @return \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface
     */
    protected function createFirstDataGuzzleResponse(?ResponseInterface $response): FirstDataGuzzleResponseInterface
    {
        if ($response === null) {
            return new FirstDataGuzzleResponse();
        }

        return new FirstDataGuzzleResponse(
            $response->getBody(),
            $response->getHeaders()
        );
    }
}
