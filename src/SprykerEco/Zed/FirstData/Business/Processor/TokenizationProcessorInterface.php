<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Processor;

use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;

interface TokenizationProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer
     */
    public function processTokenization(FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer): FirstDataCustomerTokenTransfer;
}
