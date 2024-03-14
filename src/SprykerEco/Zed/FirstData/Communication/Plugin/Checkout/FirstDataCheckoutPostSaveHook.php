<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerEco\Zed\FirstData\FirstDataConfig getConfig()
 * @method \SprykerEco\Zed\FirstData\Business\FirstDataFacadeInterface getFacade()
 * @method \SprykerEco\Zed\FirstData\Communication\FirstDataCommunicationFactory getFactory()
 */
class FirstDataCheckoutPostSaveHook extends AbstractPlugin implements CheckoutPostSaveHookInterface
{
    /**
     * {@inheritDoc}
     * - Makes API call for pre-auth transaction.
     * - Saves `cardToken` to DB and associate it with current customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executeHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->executeReservationRequest($quoteTransfer, $checkoutResponse);
    }
}
