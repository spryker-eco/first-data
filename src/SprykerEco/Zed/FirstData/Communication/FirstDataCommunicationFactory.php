<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\FirstData\FirstDataDependencyProvider;
use SprykerEco\Zed\Sales\Business\SalesFacadeInterface;

/**
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\FirstData\FirstDataConfig getConfig()
 * @method \SprykerEco\Zed\FirstData\Business\FirstDataFacadeInterface getFacade()
 */
class FirstDataCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(FirstDataDependencyProvider::FACADE_SALES);
    }
}
