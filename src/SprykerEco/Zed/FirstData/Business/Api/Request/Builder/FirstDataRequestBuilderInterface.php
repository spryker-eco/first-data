<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Request\Builder;

use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataHttpRequestTransfer;

interface FirstDataRequestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstDataApiRequestTransfer $firstDataApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataHttpRequestTransfer
     */
    public function buildRequest(FirstDataApiRequestTransfer $firstDataApiRequestTransfer): FirstDataHttpRequestTransfer;
}
