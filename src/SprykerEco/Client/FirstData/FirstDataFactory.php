<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\FirstData;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerEco\Client\FirstData\Generator\HashGenerator;
use SprykerEco\Client\FirstData\Generator\HashGeneratorInterface;
use SprykerEco\Client\FirstData\Zed\FirstDataStub;
use SprykerEco\Client\FirstData\Zed\FirstDataStubInterface;

/**
 * @method \SprykerEco\Client\FirstData\FirstDataConfig getConfig()
 */
class FirstDataFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Client\FirstData\Zed\FirstDataStubInterface
     */
    public function createZedStub(): FirstDataStubInterface
    {
        return new FirstDataStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(FirstDataDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \SprykerEco\Client\FirstData\Generator\HashGeneratorInterface
     */
    public function createHashGenerator(): HashGeneratorInterface
    {
        return new HashGenerator($this->getConfig());
    }
}
