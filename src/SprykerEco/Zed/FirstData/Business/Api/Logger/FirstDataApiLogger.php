<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Logger;

use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataHttpRequestTransfer;
use Generated\Shared\Transfer\PaymentFirstDataApiLogTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface;
use SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface;

class FirstDataApiLogger implements FirstDataApiLoggerInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface
     */
    protected $firstDataEntityManager;

    /**
     * @var \SprykerEco\Zed\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \SprykerEco\Zed\FirstData\Persistence\FirstDataEntityManagerInterface $firstDataEntityManager
     * @param \SprykerEco\Zed\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        FirstDataEntityManagerInterface $firstDataEntityManager,
        FirstDataToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->firstDataEntityManager = $firstDataEntityManager;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataHttpRequestTransfer $firstDataHttpRequestTransfer
     * @param \Generated\Shared\Transfer\FirstDataApiResponseTransfer $firstDataApiResponseTransfer
     * @param string|null $requestType
     *
     * @return void
     */
    public function logApiCall(
        FirstDataHttpRequestTransfer $firstDataHttpRequestTransfer,
        FirstDataApiResponseTransfer $firstDataApiResponseTransfer,
        ?string $requestType
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use (
            $firstDataHttpRequestTransfer,
            $firstDataApiResponseTransfer,
            $requestType
        ) {
            $this->executeSavePaymentFirstDataApiLogTransaction(
                $firstDataHttpRequestTransfer,
                $firstDataApiResponseTransfer,
                $requestType
            );
        });
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataHttpRequestTransfer $firstDataHttpRequestTransfer
     * @param \Generated\Shared\Transfer\FirstDataApiResponseTransfer $firstDataApiResponseTransfer
     * @param string|null $requestType
     *
     * @return void
     */
    protected function executeSavePaymentFirstDataApiLogTransaction(
        FirstDataHttpRequestTransfer $firstDataHttpRequestTransfer,
        FirstDataApiResponseTransfer $firstDataApiResponseTransfer,
        ?string $requestType
    ): void {
        $paymentFirstDataApiLogTransfer = new PaymentFirstDataApiLogTransfer();
        $paymentFirstDataApiLogTransfer->setRequest($this->utilEncodingService->encodeJson($firstDataHttpRequestTransfer->toArray()));
        $paymentFirstDataApiLogTransfer->setResponse($this->utilEncodingService->encodeJson($firstDataApiResponseTransfer->toArray()));
        $paymentFirstDataApiLogTransfer->setType($requestType);
        $paymentFirstDataApiLogTransfer->setIsSuccess($firstDataApiResponseTransfer->getIsSuccess());

        $this->firstDataEntityManager->savePaymentFirstDataApiLog($paymentFirstDataApiLogTransfer);
    }
}
