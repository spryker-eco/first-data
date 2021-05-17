<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Service\UtilText\UtilTextServiceInterface;
use SprykerEco\Glue\FirstData\Processor\Mapper\FirstDataCreditCardParametersMapper;
use SprykerEco\Glue\FirstData\Processor\Mapper\FirstDataCreditCardParametersMapperInterface;
use SprykerEco\Glue\FirstData\Processor\Saver\FirstDataNotificationSaver;
use SprykerEco\Glue\FirstData\Processor\Saver\FirstDataNotificationSaverInterface;
use SprykerEco\Glue\FirstData\Validator\FirstDataPaymentValidator;
use SprykerEco\Glue\FirstData\Validator\FirstDataPaymentValidatorInterface;

/**
 * @method \SprykerEco\Glue\FirstData\FirstDataConfig getConfig()
 * @method \SprykerEco\Client\FirstData\FirstDataClient getClient()
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
     * @return \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    public function getUtilTextService(): UtilTextServiceInterface
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
     * @return \SprykerEco\Glue\FirstData\Processor\Saver\FirstDataNotificationSaverInterface
     */
    public function createFirstDataNotificationSaver(): FirstDataNotificationSaverInterface
    {
        return new FirstDataNotificationSaver($this->getClient());
    }
}
