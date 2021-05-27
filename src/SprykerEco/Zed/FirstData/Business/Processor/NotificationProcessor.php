<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Processor;

use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\FirstData\FirstDataConfig;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface;

class NotificationProcessor implements NotificationProcessorInterface
{
    use TransactionTrait;

    protected const NOTIFICATION_STATUS_APPROVED = 'APPROVED';

    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface
     */
    protected $firstDataEntityManager;

    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface $firstDataEntityManager
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     */
    public function __construct(
        FirstDataEntityManagerInterface $firstDataEntityManager,
        FirstDataConfig $firstDataConfig
    ) {
        $this->firstDataEntityManager = $firstDataEntityManager;
        $this->firstDataConfig = $firstDataConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer
     */
    public function processNotification(FirstDataNotificationTransfer $firstDataNotificationTransfer): FirstDataNotificationTransfer
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($firstDataNotificationTransfer): void {
            $this->executeProcessNotificationTransaction($firstDataNotificationTransfer);
        });

        return $firstDataNotificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return void
     */
    protected function executeProcessNotificationTransaction(FirstDataNotificationTransfer $firstDataNotificationTransfer): void
    {
        $this->firstDataEntityManager->savePaymentFirstDataNotification($firstDataNotificationTransfer);
    }
}
