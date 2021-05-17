<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Expander;

use Generated\Shared\Transfer\FirstDataTransactionDataTransfer;
use Generated\Shared\Transfer\FirstDataTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Shared\FirstData\FirstDataConfig;
use SprykerEco\Zed\FirstData\Persistence\FirstDataRepositoryInterface;

class OrderExpander implements OrderExpanderInterface
{
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function loadPaymentDataByOrder(OrderTransfer $orderTransfer): OrderTransfer
    {
        $paymentTransfer = $this->getFirstDataPaymentFromOrder($orderTransfer);

        if ($paymentTransfer === null) {
            return $orderTransfer;
        }

        $paymentFirstDataTransfer = $this->firstDataRepository
            ->findPaymentFirstDataByIdSalesOrder($orderTransfer->getIdSalesOrder());

        if ($paymentFirstDataTransfer === null) {
            return $orderTransfer;
        }

        $paymentTransfer->setFirstDataCreditCard(
            (new FirstDataTransfer())
                ->setFirstDataTransactionData(
                    (new FirstDataTransactionDataTransfer())->fromArray($paymentFirstDataTransfer->toArray(), true)
                )
        );

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    protected function getFirstDataPaymentFromOrder(OrderTransfer $orderTransfer): ?PaymentTransfer
    {
        foreach ($orderTransfer->getPayments() as $payment) {
            if ($payment->getPaymentProvider() === FirstDataConfig::PAYMENT_PROVIDER_NAME_KEY) {
                return $payment;
            }
        }

        return null;
    }
}
