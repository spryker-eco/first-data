<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \SprykerEco\Glue\FirstData\FirstDataFactory getFactory()
 */
class FirstDataTokenizedPaymentCheckoutRequestValidatorPlugin extends AbstractPlugin implements CheckoutRequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks that "FirstData" payment data has all required fields when payment data is tokenized.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateAttributes(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer
    {
        return $this->getFactory()
            ->createFirstDataTokenizedPaymentValidator()
            ->validate($restCheckoutRequestAttributesTransfer);
    }
}
