<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\FirstData\Business;

use Codeception\Test\Unit;
use Pyz\Zed\FirstData\Business\FirstDataBusinessFactory;
use Pyz\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapter;
use Pyz\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapterInterface;
use Pyz\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponse;
use Pyz\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface;
use Pyz\Zed\FirstData\FirstDataConfig;
use Pyz\Zed\FirstData\Persistence\FirstDataEntityManager;
use Pyz\Zed\FirstData\Persistence\FirstDataRepository;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group AbstractFirstDataFacadeTest
 * Add your own group annotations below this line
 */
abstract class AbstractFirstDataFacadeTest extends Unit
{
    /**
     * @param array $clientResponse
     *
     * @return \Pyz\Zed\FirstData\Business\FirstDataBusinessFactory
     */
    protected function getFirstDataBusinessFactoryMock(array $clientResponse): FirstDataBusinessFactory
    {
        $mockFirstDataBusinessFactory = $this->createPartialMock(
            FirstDataBusinessFactory::class,
            [
                'createFirstDataGuzzleHttpClientAdapter',
                'getUtilEncodingService',
            ]
        );

        $mockFirstDataBusinessFactory->setConfig(new FirstDataConfig());
        $mockFirstDataBusinessFactory->setEntityManager(new FirstDataEntityManager());
        $mockFirstDataBusinessFactory->setRepository(new FirstDataRepository());

        $mockFirstDataBusinessFactory
            ->method('createFirstDataGuzzleHttpClientAdapter')
            ->willReturn($this->getFirstDataGuzzleHttpClientAdapterMock($clientResponse));
        $mockFirstDataBusinessFactory
            ->method('getUtilEncodingService')
            ->willReturn($this->getUtilEncodingServiceMock($clientResponse));

        return $mockFirstDataBusinessFactory;
    }

    /**
     * @param array $clientResponse
     *
     * @return \Pyz\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapterInterface
     */
    protected function getFirstDataGuzzleHttpClientAdapterMock(array $clientResponse): FirstDataGuzzleHttpClientAdapterInterface
    {
        $createFirstDataGuzzleHttpClientAdapterMock = $this->createPartialMock(
            FirstDataGuzzleHttpClientAdapter::class,
            [
                'post',
            ]
        );

        $createFirstDataGuzzleHttpClientAdapterMock
            ->method('post')
            ->willReturn($this->getFirstDataGuzzleResponseMock($clientResponse));

        return $createFirstDataGuzzleHttpClientAdapterMock;
    }

    /**
     * @param array $clientResponse
     *
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected function getUtilEncodingServiceMock(array $clientResponse): UtilEncodingServiceInterface
    {
        $mockUtilEncodingService = $this->createPartialMock(
            UtilEncodingService::class,
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
     *
     * @return \Pyz\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface
     */
    protected function getFirstDataGuzzleResponseMock(array $clientResponse): FirstDataGuzzleResponseInterface
    {
        $mockFirstDataGuzzleResponse = $this->createPartialMock(
            FirstDataGuzzleResponse::class,
            [
                'getResponseBody',
            ]
        );
        $mockFirstDataGuzzleResponse
            ->method('getResponseBody')
            ->willReturn(json_encode($clientResponse));

        return $mockFirstDataGuzzleResponse;
    }
}
