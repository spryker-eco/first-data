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
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class FirstDataStub implements FirstDataStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \SprykerEco\Zed\FirstData\Communication\Controller\GatewayController::processNotificationAction()
     *
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer
     */
    public function processNotificationAction(FirstDataNotificationTransfer $firstDataNotificationTransfer): FirstDataNotificationTransfer
    {
        /** @var \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer */
        $firstDataNotificationTransfer = $this->zedRequestClient->call(
            '/first-data/gateway/process-notification',
            $firstDataNotificationTransfer
        );

        return $firstDataNotificationTransfer;
    }

    /**
     * @uses \SprykerEco\Zed\FirstData\Communication\Controller\GatewayController::processTokenizationAction()
     *
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer
     */
    public function processTokenization(FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer): FirstDataCustomerTokenTransfer
    {
        /** @var \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer */
        $firstDataCustomerTokenTransfer = $this->zedRequestClient->call(
            '/first-data/gateway/process-tokenization',
            $firstDataCustomerTokenTransfer
        );

        return $firstDataCustomerTokenTransfer;
    }

    /**
     * @uses \SprykerEco\Zed\FirstData\Communication\Controller\GatewayController::getAuthorizeSessionResponseAction()
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(CustomerTransfer $customerTransfer): FirstDataApiResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\FirstDataApiResponseTransfer $firstDataApiResponseTransfer */
        $firstDataApiResponseTransfer = $this->zedRequestClient->call(
            '/first-data/gateway/get-authorize-session-response',
            $customerTransfer
        );

        return $firstDataApiResponseTransfer;
    }

    /**
     * @uses \SprykerEco\Zed\FirstData\Communication\Controller\GatewayController::getCustomerTokensCollectionAction()
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer
     */
    public function getCustomerTokensCollection(CustomerTransfer $customerTransfer): FirstDataCustomerTokensCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer $firstDataCustomerTokensCollectionTransfer */
        $firstDataCustomerTokensCollectionTransfer = $this->zedRequestClient->call(
            '/first-data/gateway/get-customer-tokens-collection',
            $customerTransfer
        );

        return $firstDataCustomerTokensCollectionTransfer;
    }
}
