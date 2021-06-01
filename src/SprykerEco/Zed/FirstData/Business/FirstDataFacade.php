<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\FirstData\Business\FirstDataBusinessFactory getFactory()
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface getRepository()
 */
class FirstDataFacade extends AbstractFacade implements FirstDataFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function savePaymentEntities(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFactory()
            ->createFirstDataOrderPaymentSaver()
            ->savePaymentEntities($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executeReservationRequest(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void
    {
        $this->getFactory()
            ->createReservationRequestExecutor()
            ->executeRequest($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer
     *
     * @return void
     */
    public function executeCancelReservationOmsCommand(FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer): void
    {
        $this->getFactory()
            ->createCancelCommandExecutor()
            ->executeOmsCommand($firstDataOmsCommandRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer
     *
     * @return void
     */
    public function executeCaptureOmsCommand(FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer): void
    {
        $this->getFactory()
            ->createCaptureCommandExecutor()
            ->executeOmsCommand($firstDataOmsCommandRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function executeCancelOmsCommand(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): FirstDataApiResponseTransfer
    {
        return $this->getFactory()
            ->createFirstDataApiClient()
            ->performApiRequest($firstDataApiRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer
     *
     * @return void
     */
    public function executeRefundOmsCommand(FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer): void
    {
        $this->getFactory()
            ->createRefundCommandExecutor()
            ->executeOmsCommand($firstDataOmsCommandRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapFirstDataPaymentToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        return $this->getFactory()
            ->createFirstDataPaymentQuoteMapper()
            ->mapFirstDataPaymentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer
     */
    public function processNotification(FirstDataNotificationTransfer $firstDataNotificationTransfer): FirstDataNotificationTransfer
    {
        return $this->getFactory()
            ->createNotificationProcessor()
            ->processNotification($firstDataNotificationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $transactionId
     *
     * @return bool
     */
    public function checkIfApprovedNotificationReceived(string $transactionId): bool
    {
        return $this->getFactory()
            ->createFirstDataNotificationChecker()
            ->checkIfApprovedNotificationReceived($transactionId);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkPaymentAuthorizationTimeOut(OrderTransfer $orderTransfer): bool
    {
        return $this->getFactory()
            ->createPaymentAuthorizationTimeOutChecker()
            ->check($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkFirstDataResponse(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $this->getFactory()
            ->createFirstDataResponseValidator()
            ->validateFirstDataResponse($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function loadPaymentDataByOrder(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createOrderExpander()
            ->loadPaymentDataByOrder($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(QuoteTransfer $quoteTransfer): FirstDataApiResponseTransfer
    {
        return $this->getFactory()
            ->createAuthorizeSessionProvider()
            ->getAuthorizeSessionResponse($quoteTransfer);
    }
}
