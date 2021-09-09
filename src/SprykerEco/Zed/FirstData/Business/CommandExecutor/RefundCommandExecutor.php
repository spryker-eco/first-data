<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\CommandExecutor;

use Generated\Shared\Transfer\FirstDataApiRequestTransfer;
use Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface;
use SprykerEco\Zed\FirstData\FirstDataConfig;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface;
use SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface;

class RefundCommandExecutor implements FirstDataCommandExecutorInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface
     */
    protected $firstDataRepository;

    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @var \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface
     */
    protected $firstDataApiClient;

    /**
     * @param \SprykerEco\Zed\FirstData\Business\Api\ApiClient\FirstDataApiClientInterface $firstDataApiClient
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface $entityManager
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface $firstDataRepository
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     */
    public function __construct(
        FirstDataApiClientInterface $firstDataApiClient,
        FirstDataEntityManagerInterface $entityManager,
        FirstDataRepositoryInterface $firstDataRepository,
        FirstDataConfig $firstDataConfig
    ) {
        $this->firstDataApiClient = $firstDataApiClient;
        $this->entityManager = $entityManager;
        $this->firstDataRepository = $firstDataRepository;
        $this->firstDataConfig = $firstDataConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer
     *
     * @return void
     */
    public function executeOmsCommand(FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer): void
    {
        $paymentFirstDataTransfer = $this->firstDataRepository
            ->findPaymentFirstDataByIdSalesOrder($firstDataOmsCommandRequestTransfer->getOrderOrFail()->getIdSalesOrderOrFail());

        if (!$paymentFirstDataTransfer) {
            return;
        }

        $firstDataApiRequestTransfer = (new FirstDataApiRequestTransfer())
            ->setRequestType(FirstDataConfig::FIRST_DATA_RETURN_REQUEST_TYPE)
            ->setOrder($firstDataOmsCommandRequestTransfer->getOrder())
            ->setTransactionId($paymentFirstDataTransfer->getOid())
            ->setStoreId($this->firstDataConfig->getStoreId())
            ->setRefund($this->getItemsForRefund($firstDataOmsCommandRequestTransfer));

        $firstDataApiResponseTransfer = $this->firstDataApiClient->performApiRequest($firstDataApiRequestTransfer);

        if (!$firstDataApiResponseTransfer->getIsSuccess()) {
            return;
        }

        $paymentFirstDataItemTransfers = $this->firstDataRepository
            ->getPaymentFirstDataItemCollection(
                $paymentFirstDataTransfer,
                $firstDataOmsCommandRequestTransfer->getSalesOrderItemIds()
            );

        $this->executeTransactionUpdatePaymentFirstDataItems($paymentFirstDataItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    protected function getItemsForRefund(FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer): RefundTransfer
    {
        $refundTransfer = new RefundTransfer();
        $refundItemTransferIds = $firstDataOmsCommandRequestTransfer->getSalesOrderItemIds();

        foreach ($firstDataOmsCommandRequestTransfer->getOrderOrFail()->getItems() as $itemTransfer) {
            if (!in_array($itemTransfer->getIdSalesOrderItem(), $refundItemTransferIds)) {
                continue;
            }

            $refundTransfer->addItem($itemTransfer);
        }

        return $refundTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataItemTransfer[] $paymentFirstDataItemTransfers
     *
     * @return void
     */
    protected function executeTransactionUpdatePaymentFirstDataItems(array $paymentFirstDataItemTransfers): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($paymentFirstDataItemTransfers): void {
            foreach ($paymentFirstDataItemTransfers as $paymentFirstDataItemTransfer) {
                $paymentFirstDataItemTransfer->setStatus($this->firstDataConfig->getOmsStatusRefunded());
                $this->entityManager->savePaymentFirstDataItem($paymentFirstDataItemTransfer);
            }
        });
    }
}
