<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Processor;

use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface;

class TokenizationProcessor implements TokenizationProcessorInterface
{
    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface
     */
    protected $firstDataEntityManager;

    /**
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface $firstDataEntityManager
     */
    public function __construct(FirstDataEntityManagerInterface $firstDataEntityManager)
    {
        $this->firstDataEntityManager = $firstDataEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer
     */
    public function processTokenization(FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer): FirstDataCustomerTokenTransfer
    {
        $this->firstDataEntityManager->tokenizeClientToken($firstDataCustomerTokenTransfer);

        return $firstDataCustomerTokenTransfer;
    }
}
