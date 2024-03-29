<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData;

use Spryker\Glue\Kernel\AbstractFactory;
use SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface;
use SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilTextServiceInterface;
use SprykerEco\Glue\FirstData\Processor\Mapper\AuthorizeSessionMapper;
use SprykerEco\Glue\FirstData\Processor\Mapper\AuthorizeSessionMapperInterface;
use SprykerEco\Glue\FirstData\Processor\Mapper\FirstDataCreditCardParametersMapper;
use SprykerEco\Glue\FirstData\Processor\Mapper\FirstDataCreditCardParametersMapperInterface;
use SprykerEco\Glue\FirstData\Processor\Mapper\FirstDataCustomerTokenMapper;
use SprykerEco\Glue\FirstData\Processor\Mapper\FirstDataCustomerTokenMapperInterface;
use SprykerEco\Glue\FirstData\Processor\Provider\AuthorizeSessionProvider;
use SprykerEco\Glue\FirstData\Processor\Provider\AuthorizeSessionProviderInterface;
use SprykerEco\Glue\FirstData\Processor\Saver\FirstDataNotificationSaver;
use SprykerEco\Glue\FirstData\Processor\Saver\FirstDataNotificationSaverInterface;
use SprykerEco\Glue\FirstData\Processor\Saver\FirstDataTokenizationSaver;
use SprykerEco\Glue\FirstData\Processor\Saver\FirstDataTokenizationSaverInterface;
use SprykerEco\Glue\FirstData\Validator\FirstDataPaymentValidator;
use SprykerEco\Glue\FirstData\Validator\FirstDataPaymentValidatorInterface;
use SprykerEco\Glue\FirstData\Validator\FirstDataTokenizedPaymentValidator;

/**
 * @method \SprykerEco\Glue\FirstData\FirstDataConfig getConfig()
 * @method \SprykerEco\Client\FirstData\FirstDataClient getClient()
 * @method \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface getResourceBuilder()
 */
class FirstDataFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Glue\FirstData\Processor\Mapper\FirstDataCreditCardParametersMapperInterface
     */
    public function createFirstDataCreditCardParametersMapper(): FirstDataCreditCardParametersMapperInterface
    {
        return new FirstDataCreditCardParametersMapper(
            $this->getConfig(),
            $this->getClient(),
            $this->getUtilTextService(),
        );
    }

    /**
     * @return \SprykerEco\Glue\FirstData\Processor\Mapper\FirstDataCustomerTokenMapperInterface
     */
    public function createFirstDataCustomerTokenMapper(): FirstDataCustomerTokenMapperInterface
    {
        return new FirstDataCustomerTokenMapper($this->getClient());
    }

    /**
     * @return \SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilTextServiceInterface
     */
    public function getUtilTextService(): FirstDataToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(FirstDataDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \SprykerEco\Glue\FirstData\Validator\FirstDataPaymentValidatorInterface
     */
    public function createFirstDataPaymentValidator(): FirstDataPaymentValidatorInterface
    {
        return new FirstDataPaymentValidator();
    }

    /**
     * @return \SprykerEco\Glue\FirstData\Validator\FirstDataPaymentValidatorInterface
     */
    public function createFirstDataTokenizedPaymentValidator(): FirstDataPaymentValidatorInterface
    {
        return new FirstDataTokenizedPaymentValidator();
    }

    /**
     * @return \SprykerEco\Glue\FirstData\Processor\Saver\FirstDataNotificationSaverInterface
     */
    public function createFirstDataNotificationSaver(): FirstDataNotificationSaverInterface
    {
        return new FirstDataNotificationSaver($this->getClient());
    }

    /**
     * @return \SprykerEco\Glue\FirstData\Processor\Saver\FirstDataTokenizationSaverInterface
     */
    public function createFirstDataTokenizationSaver(): FirstDataTokenizationSaverInterface
    {
        return new FirstDataTokenizationSaver($this->getClient(), $this->getUtilEncodingService());
    }

    /**
     * @return \SprykerEco\Glue\FirstData\Processor\Provider\AuthorizeSessionProviderInterface
     */
    public function createAuthorizeSessionProvider(): AuthorizeSessionProviderInterface
    {
        return new AuthorizeSessionProvider(
            $this->getClient(),
            $this->getResourceBuilder(),
            $this->createAuthorizeSessionMapper()
        );
    }

    /**
     * @return \SprykerEco\Glue\FirstData\Processor\Mapper\AuthorizeSessionMapperInterface
     */
    public function createAuthorizeSessionMapper(): AuthorizeSessionMapperInterface
    {
        return new AuthorizeSessionMapper();
    }

    /**
     * @return \SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): FirstDataToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(FirstDataDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
