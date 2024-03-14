<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Reader;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer;
use SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface;

class FirstDataCustomerTokenReader implements FirstDataCustomerTokenReaderInterface
{
    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface
     */
    protected $firstDataRepository;

    /**
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface $firstDataRepository
     */
    public function __construct(FirstDataRepositoryInterface $firstDataRepository)
    {
        $this->firstDataRepository = $firstDataRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer
     */
    public function getFirstDataCustomerTokensCollection(CustomerTransfer $customerTransfer): FirstDataCustomerTokensCollectionTransfer
    {
        return $this->firstDataRepository->findPaymentFirstDataCustomerTokensCollection($customerTransfer);
    }
}
