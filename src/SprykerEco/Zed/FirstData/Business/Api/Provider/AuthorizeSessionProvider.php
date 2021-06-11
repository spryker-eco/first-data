<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Provider;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface;
use SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface;

class AuthorizeSessionProvider implements AuthorizeSessionProviderInterface
{
    use TransactionTrait;

    protected const AUTHORIZE_SESSION_REQUEST_TYPE_NAME = 'AuthorizeSession';

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface
     */
    protected $firstDataApiClient;

    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface
     */
    protected $firstDataRepository;

    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface $firstDataApiClient
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface $firstDataRepository
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface $entityManager
     */
    public function __construct(
        FirstDataApiClientInterface $firstDataApiClient,
        FirstDataRepositoryInterface $firstDataRepository,
        FirstDataEntityManagerInterface $entityManager
    ) {
        $this->firstDataApiClient = $firstDataApiClient;
        $this->firstDataRepository = $firstDataRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(CustomerTransfer $customerTransfer): FirstDataApiResponseTransfer
    {
        $firstDataApiResponseTransfer = $this->firstDataApiClient->performApiRequest(
            (new FirstDataApiRequestTransfer())->setRequestType(static::AUTHORIZE_SESSION_REQUEST_TYPE_NAME)
        );

        $firstDataCustomerTokenTransfer = (new FirstDataCustomerTokenTransfer())->setClientToken(
            $firstDataApiResponseTransfer->getAuthorizeSessionResponseOrFail()->getClientToken()
        );

        $this->entityManager->attachCardTokenToCustomer($customerTransfer, $firstDataCustomerTokenTransfer);

        return $firstDataApiResponseTransfer;
    }
}
