<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\FirstData\Zed;

use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @uses \SprykerEco\Zed\FirstData\Communication\Controller\GatewayController::getAuthorizeSessionResponseAction()
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(QuoteTransfer $quoteTransfer): FirstDataApiResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\FirstDataApiResponseTransfer $firstDataApiResponseTransfer */
        $firstDataApiResponseTransfer = $this->zedRequestClient->call(
            '/first-data/gateway/get-authorize-session-response',
            $quoteTransfer
        );

        return $firstDataApiResponseTransfer;
    }
}
