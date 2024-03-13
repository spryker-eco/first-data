<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\FirstData\Business;

use Codeception\Test\Unit;
use SprykerEco\Zed\FirstData\Business\FirstDataBusinessFactory;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapter;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapterInterface;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponse;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface;
use SprykerEco\Zed\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceBridge;
use SprykerEco\Zed\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface;
use SprykerEco\Zed\FirstData\FirstDataConfig;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManager;
use SprykerEco\Zed\FirstData\Persistence\FirstDataPersistenceFactory;
use SprykerEco\Zed\FirstData\Persistence\FirstDataRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerEcoTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group AbstractFirstDataFacadeTest
 */
abstract class AbstractFirstDataFacadeTest extends Unit
{
    protected const CLIENT_TOKEN_HEADER_KEY = 'Client-Token';
    protected const TEST_CLIENT_TOKEN = 'clientToken';

    /**
     * @param array $clientResponse
     * @param array $clientHeaders
     *
     * @return \SprykerEco\Zed\FirstData\Business\FirstDataBusinessFactory
     */
    protected function getFirstDataBusinessFactoryMock(array $clientResponse = [], array $clientHeaders = []): FirstDataBusinessFactory
    {
        $mockFirstDataBusinessFactory = $this->createPartialMock(
            FirstDataBusinessFactory::class,
            [
                'createFirstDataGuzzleHttpClientAdapter',
                'getUtilEncodingService',
            ]
        );

        $mockFirstDataBusinessFactory->setConfig(new FirstDataConfig());
        $mockFirstDataBusinessFactory->setEntityManager((new FirstDataEntityManager())->setFactory(new FirstDataPersistenceFactory()));
        $mockFirstDataBusinessFactory->setRepository((new FirstDataRepository())->setFactory(new FirstDataPersistenceFactory()));

        $mockFirstDataBusinessFactory
            ->method('createFirstDataGuzzleHttpClientAdapter')
            ->willReturn($this->getFirstDataGuzzleHttpClientAdapterMock($clientResponse, $clientHeaders));
        $mockFirstDataBusinessFactory
            ->method('getUtilEncodingService')
            ->willReturn($this->getUtilEncodingServiceMock($clientResponse));

        return $mockFirstDataBusinessFactory;
    }

    /**
     * @param array $clientResponse
     * @param array $clientHeaders
     *
     * @return \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapterInterface
     */
    protected function getFirstDataGuzzleHttpClientAdapterMock(array $clientResponse, array $clientHeaders): FirstDataGuzzleHttpClientAdapterInterface
    {
        $createFirstDataGuzzleHttpClientAdapterMock = $this->createPartialMock(
            FirstDataGuzzleHttpClientAdapter::class,
            [
                'post',
            ]
        );

        $createFirstDataGuzzleHttpClientAdapterMock
            ->method('post')
            ->willReturn($this->getFirstDataGuzzleResponseMock($clientResponse, $clientHeaders));

        return $createFirstDataGuzzleHttpClientAdapterMock;
    }

    /**
     * @param array $clientResponse
     *
     * @return \SprykerEco\Zed\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingServiceMock(array $clientResponse): FirstDataToUtilEncodingServiceInterface
    {
        $mockUtilEncodingService = $this->createPartialMock(
            FirstDataToUtilEncodingServiceBridge::class,
            [
                'decodeJson',
                'encodeJson',
            ]
        );
        $mockUtilEncodingService
            ->method('decodeJson')
            ->willReturn($clientResponse);
        $mockUtilEncodingService
            ->method('encodeJson')
            ->willReturn(json_encode($clientResponse));

        return $mockUtilEncodingService;
    }

    /**
     * @param array $clientResponse
     * @param array $clientHeaders
     *
     * @return \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface
     */
    protected function getFirstDataGuzzleResponseMock(array $clientResponse, array $clientHeaders): FirstDataGuzzleResponseInterface
    {
        $mockFirstDataGuzzleResponse = $this->createPartialMock(
            FirstDataGuzzleResponse::class,
            [
                'getResponseBody',
                'getHeaders',
                'getHeader',
            ]
        );

        $mockFirstDataGuzzleResponse
            ->method('getResponseBody')
            ->willReturn(json_encode($clientResponse));

        $mockFirstDataGuzzleResponse
            ->method('getHeaders')
            ->willReturn($clientHeaders);

        $mockFirstDataGuzzleResponse
            ->method('getHeader')
            ->willReturnCallback(function (string $header) {
                if ($header === static::CLIENT_TOKEN_HEADER_KEY) {
                    return static::TEST_CLIENT_TOKEN;
                }
            });

        return $mockFirstDataGuzzleResponse;
    }
}
