<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Provider;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;

interface AuthorizeSessionProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(CustomerTransfer $customerTransfer): FirstDataApiResponseTransfer;
}
