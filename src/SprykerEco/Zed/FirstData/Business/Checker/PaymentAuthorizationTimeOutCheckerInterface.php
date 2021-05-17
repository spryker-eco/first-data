<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Checker;

use Generated\Shared\Transfer\OrderTransfer;

interface PaymentAuthorizationTimeOutCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function check(OrderTransfer $orderTransfer): bool;
}
