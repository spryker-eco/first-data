<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Processor\Mapper;

use Generated\Shared\Transfer\AuthorizeSessionResponseTransfer;
use Generated\Shared\Transfer\RestAuthorizeSessionResponseAttributesTransfer;

class AuthorizeSessionMapper implements AuthorizeSessionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AuthorizeSessionResponseTransfer $authorizeSessionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestAuthorizeSessionResponseAttributesTransfer
     */
    public function mapAuthorizeSessionResponseTransferToRestAuthorizeSessionResponseAttributesTransfer(
        AuthorizeSessionResponseTransfer $authorizeSessionResponseTransfer
    ): RestAuthorizeSessionResponseAttributesTransfer {
        return (new RestAuthorizeSessionResponseAttributesTransfer())
            ->fromArray(
                $authorizeSessionResponseTransfer->toArray(),
                true
            );
    }
}
