<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Processor\Mapper;

use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use SprykerEco\Client\FirstData\FirstDataClientInterface;

class FirstDataAuthorizeSessionParametersMapper implements FirstDataAuthorizeSessionParametersMapperInterface
{
    /**
     * @var \SprykerEco\Client\FirstData\FirstDataClientInterface
     */
    protected $firstDataClient;

    /**
     * @param \SprykerEco\Client\FirstData\FirstDataClientInterface $firstDataClient
     */
    public function __construct(FirstDataClientInterface $firstDataClient)
    {
        $this->firstDataClient = $firstDataClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapFirstDataAuthorizeSessionParametersTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer,
        RestCheckoutDataTransfer $restCheckoutDataTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $firstDataApiResponseTransfer = $this->firstDataClient->getAuthorizeSessionResponse();

        $restCheckoutDataResponseAttributesTransfer->setFirstDataAuthorizeSessionParameters(
            $firstDataApiResponseTransfer->getAuthorizeSessionresponseOrFail()
        );

        return $restCheckoutDataResponseAttributesTransfer;
    }
}
