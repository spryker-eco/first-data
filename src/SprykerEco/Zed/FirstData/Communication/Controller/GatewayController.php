<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Communication\Controller;

use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponseAction(QuoteTransfer $quoteTransfer): FirstDataApiResponseTransfer
    {
        return $this->getFacade()->getAuthorizeSessionResponse($quoteTransfer);
    }
}
