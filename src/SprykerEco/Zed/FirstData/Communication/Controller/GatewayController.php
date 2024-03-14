<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerEco\Zed\FirstData\Business\FirstDataFacade getFacade()
 * @method \SprykerEco\Zed\FirstData\Communication\FirstDataCommunicationFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer
     */
    public function processNotificationAction(FirstDataNotificationTransfer $firstDataNotificationTransfer): FirstDataNotificationTransfer
    {
        return $this->getFacade()->processNotification($firstDataNotificationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponseAction(CustomerTransfer $customerTransfer): FirstDataApiResponseTransfer
    {
        return $this->getFacade()->getAuthorizeSessionResponse($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer
     */
    public function getCustomerTokensCollectionAction(CustomerTransfer $customerTransfer): FirstDataCustomerTokensCollectionTransfer
    {
        return $this->getFacade()->getFirstDataCustomerTokensCollection($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer
     */
    public function processTokenizationAction(FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer): FirstDataCustomerTokenTransfer
    {
        return $this->getFacade()->processTokenization($firstDataCustomerTokenTransfer);
    }
}
