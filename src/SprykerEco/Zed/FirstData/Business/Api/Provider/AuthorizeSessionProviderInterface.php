<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Provider;

use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface AuthorizeSessionProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(QuoteTransfer $quoteTransfer): FirstDataApiResponseTransfer;
}
