<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Saver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentFirstDataItemTransfer;
use Generated\Shared\Transfer\PaymentFirstDataTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Shared\FirstData\FirstDataConfig as SharedFirstDataConfig;
use SprykerEco\Zed\FirstData\FirstDataConfig;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface;

class FirstDataOrderPaymentSaver implements FirstDataOrderPaymentSaverInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface $entityManager
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     */
    public function __construct(
        FirstDataEntityManagerInterface $entityManager,
        FirstDataConfig $firstDataConfig
    ) {
        $this->entityManager = $entityManager;
        $this->firstDataConfig = $firstDataConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function savePaymentEntities(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $paymentTransfer = $quoteTransfer->getPayment();

        if (!$paymentTransfer || $paymentTransfer->getPaymentProvider() !== SharedFirstDataConfig::PAYMENT_PROVIDER_NAME_KEY) {
            return;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer, $saveOrderTransfer): void {
            $this->executeSavePaymentEntitiesTransaction($quoteTransfer, $saveOrderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function executeSavePaymentEntitiesTransaction(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $paymentFirstDataTransfer = $this->savePaymentFirstData($quoteTransfer, $saveOrderTransfer);

        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $this->savePaymentFirstDataItem($paymentFirstDataTransfer, $itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataTransfer
     */
    protected function savePaymentFirstData(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): PaymentFirstDataTransfer {
        $firstDataTransactionDataTransfer = $quoteTransfer
            ->getPaymentOrFail()
            ->getFirstDataCreditCardOrFail()
            ->getFirstDataTransactionDataOrFail();

        $paymentFirstDataTransfer = (new PaymentFirstDataTransfer())
            ->fromArray($firstDataTransactionDataTransfer->toArray(), true)
            ->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference());

        return $this->entityManager->savePaymentFirstData($paymentFirstDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentFirstDataTransfer $paymentFirstDataTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentFirstDataItemTransfer
     */
    protected function savePaymentFirstDataItem(
        PaymentFirstDataTransfer $paymentFirstDataTransfer,
        ItemTransfer $itemTransfer
    ): PaymentFirstDataItemTransfer {
        $paymentFirstDataItemTransfer = (new PaymentFirstDataItemTransfer())
            ->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->setFkPaymentFirstData($paymentFirstDataTransfer->getIdPaymentFirstData())
            ->setStatus($this->firstDataConfig->getOmsStatusNew())
            ->setTransactionId($paymentFirstDataTransfer->getTransactionId());

        return $this->entityManager->savePaymentFirstDataItem($paymentFirstDataItemTransfer);
    }
}
