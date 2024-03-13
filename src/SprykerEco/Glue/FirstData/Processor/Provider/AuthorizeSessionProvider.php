<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Processor\Provider;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use SprykerEco\Client\FirstData\FirstDataClientInterface;
use SprykerEco\Glue\FirstData\FirstDataConfig;
use SprykerEco\Glue\FirstData\Processor\Mapper\AuthorizeSessionMapperInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeSessionProvider implements AuthorizeSessionProviderInterface
{
    /**
     * @var \SprykerEco\Client\FirstData\FirstDataClientInterface
     */
    protected $firstDataClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \SprykerEco\Glue\FirstData\Processor\Mapper\AuthorizeSessionMapperInterface
     */
    protected $authorizeSessionMapper;

    /**
     * @param \SprykerEco\Client\FirstData\FirstDataClientInterface $firstDataClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \SprykerEco\Glue\FirstData\Processor\Mapper\AuthorizeSessionMapperInterface $authorizeSessionMapper
     */
    public function __construct(
        FirstDataClientInterface $firstDataClient,
        RestResourceBuilderInterface $restResourceBuilder,
        AuthorizeSessionMapperInterface $authorizeSessionMapper
    ) {
        $this->firstDataClient = $firstDataClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->authorizeSessionMapper = $authorizeSessionMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAuthorizeSessionResponse(CustomerTransfer $customerTransfer): RestResponseInterface
    {
        $customerTransfer->requireCustomerReference();
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $firstDataApiResponseTransfer = $this->firstDataClient->getAuthorizeSessionResponse($customerTransfer);

        if (!$firstDataApiResponseTransfer->getIsSuccess()) {
            $restErrorTransfer = $this->createRestErrorTransfer($firstDataApiResponseTransfer);

            return $restResponse->addError($restErrorTransfer);
        }

        return $restResponse->addResource($this->createRestResource($firstDataApiResponseTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiResponseTransfer $firstDataApiResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createRestResource(FirstDataApiResponseTransfer $firstDataApiResponseTransfer): RestResourceInterface
    {
        $restAuthorizeSessionResponseAttributesTransfer = $this->authorizeSessionMapper
            ->mapAuthorizeSessionResponseTransferToRestAuthorizeSessionResponseAttributesTransfer(
                $firstDataApiResponseTransfer->getAuthorizeSessionResponseOrFail()
            );

        return $this
            ->restResourceBuilder
            ->createRestResource(
                FirstDataConfig::RESOURCE_FIRST_DATA_AUTHORIZE_SESSION,
                null,
                $restAuthorizeSessionResponseAttributesTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataApiResponseTransfer $firstDataApiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createRestErrorTransfer(FirstDataApiResponseTransfer $firstDataApiResponseTransfer): RestErrorMessageTransfer
    {
        $error = $firstDataApiResponseTransfer->getErrorOrFail();

        return (new RestErrorMessageTransfer())
            ->setCode($error->getCode())
            ->setStatus(Response::HTTP_EXPECTATION_FAILED)
            ->setDetail($error->getMessage());
    }
}
