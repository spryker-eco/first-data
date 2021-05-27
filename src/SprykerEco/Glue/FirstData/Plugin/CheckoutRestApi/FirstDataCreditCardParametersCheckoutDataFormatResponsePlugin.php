<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Plugin\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseDataPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class FirstDataCreditCardParametersCheckoutDataFormatResponsePlugin extends AbstractPlugin implements FormatResponseDataPluginInterface
{
    /**
     * {@inheritDoc}
     * - Formats `data.attributes.firstDataCreditCardParameters` in checkout data response to get rid of null values.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $request
     * @param array $preparedResponseData
     *
     * @return array
     */
    public function format(RestRequestInterface $request, array $preparedResponseData): array
    {
        if ($request->getResource()->getType() !== CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA
            || empty($preparedResponseData['data']['attributes']['firstDataCreditCardParameters'])
        ) {
            return $preparedResponseData;
        }

        $parameters = $preparedResponseData['data']['attributes']['firstDataCreditCardParameters'];
        $firstDataCreditCardParameters = array_filter($parameters);
        $preparedResponseData['data']['attributes']['firstDataCreditCardParameters'] = $firstDataCreditCardParameters;

        return $preparedResponseData;
    }
}
