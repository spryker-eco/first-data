<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Checker;

interface FirstDataNotificationCheckerInterface
{
    /**
     * @param string $transactionId
     *
     * @return bool
     */
    public function checkIfApprovedNotificationReceived(string $transactionId): bool;
}
