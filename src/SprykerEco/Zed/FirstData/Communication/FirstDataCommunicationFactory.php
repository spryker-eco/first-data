<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\FirstData\Dependency\Facade\FirstDataToSalesFacadeInterface;
use SprykerEco\Zed\FirstData\FirstDataDependencyProvider;

/**
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\FirstData\FirstDataConfig getConfig()
 * @method \SprykerEco\Zed\FirstData\Business\FirstDataFacadeInterface getFacade()
 */
class FirstDataCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\FirstData\Dependency\Facade\FirstDataToSalesFacadeInterface
     */
    public function getSalesFacade(): FirstDataToSalesFacadeInterface
    {
        return $this->getProvidedDependency(FirstDataDependencyProvider::FACADE_SALES);
    }
}
