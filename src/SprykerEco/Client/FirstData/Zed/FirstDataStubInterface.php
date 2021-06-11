<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\FirstData\Zed;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
use Generated\Shared\Transfer\FirstDataNotificationTransfer;

interface FirstDataStubInterface
{
    /**
     * @uses \SprykerEco\Zed\FirstData\Communication\Controller\GatewayController::processNotificationAction()
     *
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer
     */
    public function processNotificationAction(FirstDataNotificationTransfer $firstDataNotificationTransfer): FirstDataNotificationTransfer;

    /**
     * @uses \SprykerEco\Zed\FirstData\Communication\Controller\GatewayController::processTokenizationAction()
     *
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer
     */
    public function processTokenization(FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer): FirstDataCustomerTokenTransfer;

    /**
     * @uses \SprykerEco\Zed\FirstData\Communication\Controller\GatewayController::getAuthorizeSessionResponseTransferAction()
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(CustomerTransfer $customerTransfer): FirstDataApiResponseTransfer;

        /**
         * @uses \SprykerEco\Zed\FirstData\Communication\Controller\GatewayController::getCustomerTokensCollectionAction()
         *
         * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
         *
         * @return \Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer
         */
    public function getCustomerTokensCollection(CustomerTransfer $customerTransfer): FirstDataCustomerTokensCollectionTransfer;
}
