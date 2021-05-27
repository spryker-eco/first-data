<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Provider;

use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface;
use SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface;

class AuthorizeSessionProvider implements AuthorizeSessionProviderInterface
{
    protected const AUTHORIZE_SESSION_REQUEST_TYPE_NAME = 'AuthorizeSession';

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface
     */
    protected $firstDataApiClient;

    /**
     * @param \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface $firstDataApiClient
     */
    public function __construct(
        FirstDataApiClientInterface $firstDataApiClient,
        FirstDataRepositoryInterface $firstDataRepository
    ) {
        $this->firstDataApiClient = $firstDataApiClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(QuoteTransfer $quoteTransfer): FirstDataApiResponseTransfer
    {
        $firstDataApiResponseTransfer = $this->firstDataApiClient->performApiRequest(
            (new FirstDataApiRequestTransfer())->setRequestType(static::AUTHORIZE_SESSION_REQUEST_TYPE_NAME)
        );

        $firstDataApiResponseTransfer->getAuthorizeSessionresponseOrFail()->setCustomerTokens();
    }
}
