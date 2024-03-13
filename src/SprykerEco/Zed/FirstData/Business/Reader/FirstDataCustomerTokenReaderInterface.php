<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Reader;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer;

interface FirstDataCustomerTokenReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer
     */
    public function getFirstDataCustomerTokensCollection(CustomerTransfer $customerTransfer): FirstDataCustomerTokensCollectionTransfer;
}
