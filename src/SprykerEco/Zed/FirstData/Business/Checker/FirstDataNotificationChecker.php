<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Checker;

use SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface;

class FirstDataNotificationChecker implements FirstDataNotificationCheckerInterface
{
    protected const NOTIFICATION_STATUS_APPROVED = 'APPROVED';

    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface
     */
    protected $firstDataRepository;

    /**
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface $firstDataRepository
     */
    public function __construct(FirstDataRepositoryInterface $firstDataRepository)
    {
        $this->firstDataRepository = $firstDataRepository;
    }

    /**
     * @param string $transactionId
     *
     * @return bool
     */
    public function checkIfApprovedNotificationReceived(string $transactionId): bool
    {
        $firstDataNotification = $this->firstDataRepository->findPaymentFirstDataNotificationByTransactionId($transactionId);

        if (!$firstDataNotification) {
            return false;
        }

        return $firstDataNotification->getStatus() === static::NOTIFICATION_STATUS_APPROVED;
    }
}
