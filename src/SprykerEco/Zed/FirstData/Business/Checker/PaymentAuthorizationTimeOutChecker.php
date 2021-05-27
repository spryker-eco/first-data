<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Checker;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\FirstData\FirstDataConfig;

class PaymentAuthorizationTimeOutChecker implements PaymentAuthorizationTimeOutCheckerInterface
{
    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     */
    public function __construct(FirstDataConfig $firstDataConfig)
    {
        $this->firstDataConfig = $firstDataConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function check(OrderTransfer $orderTransfer): bool
    {
        $paymentAuthTimedOutDateTime = new DateTime($orderTransfer->getCreatedAtOrFail());
        $interval = DateInterval::createFromDateString($this->firstDataConfig->getPaymentAuthorizationTimeOut());
        $paymentAuthTimedOutDateTime->add($interval);

        return new DateTime() > $paymentAuthTimedOutDateTime;
    }
}
