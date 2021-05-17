<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business;

use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Client\FirstData\FirstDataClientInterface;
use SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClient;
use SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface;
use SprykerEco\Zed\FirstData\Business\Api\Generator\HashGenerator;
use SprykerEco\Zed\FirstData\Business\Api\Generator\HashGeneratorInterface;
use SprykerEco\Zed\FirstData\Business\Api\Logger\FirstDataApiLogger;
use SprykerEco\Zed\FirstData\Business\Api\Logger\FirstDataApiLoggerInterface;
use SprykerEco\Zed\FirstData\Business\Api\Request\Builder\FirstDataRequestBuilder;
use SprykerEco\Zed\FirstData\Business\Api\Request\Builder\FirstDataRequestBuilderInterface;
use SprykerEco\Zed\FirstData\Business\Api\Request\Converter\CaptureRequestConverter;
use SprykerEco\Zed\FirstData\Business\Api\Request\Converter\FirstDataRequestConverterInterface;
use SprykerEco\Zed\FirstData\Business\Api\Request\Converter\RefundRequestConverter;
use SprykerEco\Zed\FirstData\Business\Api\Request\Converter\ReservationRequestConverter;
use SprykerEco\Zed\FirstData\Business\Api\Response\Converter\FirstDataResponseConverter;
use SprykerEco\Zed\FirstData\Business\Api\Response\Converter\FirstDataResponseConverterInterface;
use SprykerEco\Zed\FirstData\Business\Checker\FirstDataNotificationChecker;
use SprykerEco\Zed\FirstData\Business\Checker\FirstDataNotificationCheckerInterface;
use SprykerEco\Zed\FirstData\Business\Checker\FirstDataResponseValidator;
use SprykerEco\Zed\FirstData\Business\Checker\FirstDataResponseValidatorInterface;
use SprykerEco\Zed\FirstData\Business\Checker\PaymentAuthorizationTimeOutChecker;
use SprykerEco\Zed\FirstData\Business\Checker\PaymentAuthorizationTimeOutCheckerInterface;
use SprykerEco\Zed\FirstData\Business\CommandExecutor\CancelCommandExecutor;
use SprykerEco\Zed\FirstData\Business\CommandExecutor\CaptureCommandExecutor;
use SprykerEco\Zed\FirstData\Business\CommandExecutor\FirstDataCommandExecutorInterface;
use SprykerEco\Zed\FirstData\Business\Expander\OrderExpander;
use SprykerEco\Zed\FirstData\Business\Expander\OrderExpanderInterface;
use SprykerEco\Zed\FirstData\Business\Mapper\FirstDataPaymentQuoteMapper;
use SprykerEco\Zed\FirstData\Business\Mapper\FirstDataPaymentQuoteMapperInterface;
use SprykerEco\Zed\FirstData\Business\Processor\NotificationProcessor;
use SprykerEco\Zed\FirstData\Business\Processor\NotificationProcessorInterface;
use SprykerEco\Zed\FirstData\Business\Saver\FirstDataOrderPaymentSaver;
use SprykerEco\Zed\FirstData\Business\Saver\FirstDataOrderPaymentSaverInterface;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapter;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapterInterface;
use SprykerEco\Zed\FirstData\FirstDataDependencyProvider;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManager;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface;
use SprykerEco\Zed\Sales\Business\SalesFacadeInterface;

/**
 * @method \SprykerEco\Zed\FirstData\FirstDataConfig getConfig()
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface getEntityManager()
 */
class FirstDataBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\FirstData\Business\Saver\FirstDataOrderPaymentSaverInterface
     */
    public function createFirstDataOrderPaymentSaver(): FirstDataOrderPaymentSaverInterface
    {
        return new FirstDataOrderPaymentSaver(
            $this->getEntityManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Checker\PaymentAuthorizationTimeOutCheckerInterface
     */
    public function createPaymentAuthorizationTimeOutChecker(): PaymentAuthorizationTimeOutCheckerInterface
    {
        return new PaymentAuthorizationTimeOutChecker($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\CommandExecutor\FirstDataCommandExecutorInterface
     */
    public function createCaptureCommandExecutor(): FirstDataCommandExecutorInterface
    {
        return new CaptureCommandExecutor(
            $this->createFirstDataApiClient(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\CommandExecutor\FirstDataCommandExecutorInterface
     */
    public function createCancelCommandExecutor(): FirstDataCommandExecutorInterface
    {
        return new CancelCommandExecutor(
            $this->createFirstDataApiClient(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface
     */
    public function createFirstDataApiClient(): FirstDataApiClientInterface
    {
        return new FirstDataApiClient(
            $this->createFirstDataGuzzleHttpClientAdapter(),
            $this->createFirstDataRequestBuilder(),
            $this->createFirstDataResponseConverter(),
            $this->createFirstDataApiLogger(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\FirstDataGuzzleHttpClientAdapterInterface
     */
    public function createFirstDataGuzzleHttpClientAdapter(): FirstDataGuzzleHttpClientAdapterInterface
    {
        return new FirstDataGuzzleHttpClientAdapter();
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Api\Request\Builder\FirstDataRequestBuilderInterface
     */
    public function createFirstDataRequestBuilder(): FirstDataRequestBuilderInterface
    {
        return new FirstDataRequestBuilder(
            $this->createFirstDataRequestConverters(),
            $this->getUtilEncodingService(),
            $this->getConfig(),
            $this->createHashGenerator()
        );
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Mapper\FirstDataPaymentQuoteMapperInterface
     */
    public function createFirstDataPaymentQuoteMapper(): FirstDataPaymentQuoteMapperInterface
    {
        return new FirstDataPaymentQuoteMapper();
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Api\Response\Converter\FirstDataResponseConverterInterface
     */
    public function createFirstDataResponseConverter(): FirstDataResponseConverterInterface
    {
        return new FirstDataResponseConverter($this->getUtilEncodingService());
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Api\Logger\FirstDataApiLoggerInterface
     */
    public function createFirstDataApiLogger(): FirstDataApiLoggerInterface
    {
        return new FirstDataApiLogger(
            $this->createFirstDataEntityManager(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Api\Request\Converter\FirstDataRequestConverterInterface[]
     */
    public function createFirstDataRequestConverters(): array
    {
        return [
            $this->createReservationRequestConverter(),
            $this->createCaptureRequestConverter(),
            $this->createRefundRequestConverter(),
        ];
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Checker\FirstDataResponseValidatorInterface
     */
    public function createFirstDataResponseValidator(): FirstDataResponseValidatorInterface
    {
        return new FirstDataResponseValidator($this->getFirstDataClient(), $this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Api\Request\Converter\FirstDataRequestConverterInterface
     */
    public function createReservationRequestConverter(): FirstDataRequestConverterInterface
    {
        return new ReservationRequestConverter();
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Api\Request\Converter\FirstDataRequestConverterInterface
     */
    public function createCaptureRequestConverter(): FirstDataRequestConverterInterface
    {
        return new CaptureRequestConverter();
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Api\Request\Converter\FirstDataRequestConverterInterface
     */
    public function createRefundRequestConverter(): FirstDataRequestConverterInterface
    {
        return new RefundRequestConverter();
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface
     */
    public function createFirstDataEntityManager(): FirstDataEntityManagerInterface
    {
        return new FirstDataEntityManager();
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Api\Generator\HashGeneratorInterface
     */
    public function createHashGenerator(): HashGeneratorInterface
    {
        return new HashGenerator($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Processor\NotificationProcessorInterface
     */
    public function createNotificationProcessor(): NotificationProcessorInterface
    {
        return new NotificationProcessor(
            $this->getEntityManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Checker\FirstDataNotificationCheckerInterface
     */
    public function createFirstDataNotificationChecker(): FirstDataNotificationCheckerInterface
    {
        return new FirstDataNotificationChecker($this->getRepository());
    }

    /**
     * @return \SprykerEco\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(FirstDataDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(FirstDataDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerEco\Client\FirstData\FirstDataClientInterface
     */
    public function getFirstDataClient(): FirstDataClientInterface
    {
        return $this->getProvidedDependency(FirstDataDependencyProvider::CLIENT_FIRST_DATA);
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander(
            $this->getRepository()
        );
    }
}
