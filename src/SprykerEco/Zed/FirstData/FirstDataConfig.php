<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\FirstData\FirstDataConstants;

class FirstDataConfig extends AbstractBundleConfig
{
    protected const MAX_CAPTURE_RETRY_COUNT = 3;
    protected const MAX_CANCEL_RETRY_COUNT = 3;
    protected const OMS_STATUS_NEW = 'new';
    protected const OMS_STATUS_AUTHORIZED = 'authorized';
    protected const OMS_STATUS_CAPTURE_REQUESTED = 'capture requested';
    protected const OMS_STATUS_CAPTURED = 'captured';
    protected const OMS_STATUS_CAPTURE_RETRY = 'payment capture retry';
    protected const STATE_CANCEL_REQUESTED = 'cancel requested';
    protected const OMS_STATUS_CANCELED = 'canceled';
    protected const OMS_STATUS_REFUNDED = 'refunded';
    protected const OMS_STATUS_CANCELLATION_RETRY = 'payment cancellation retry required';

    public const FIRST_DATA_RESERVATION_REQUEST_TYPE = 'PaymentTokenPreAuthTransaction';
    public const FIRST_DATA_CANCEL_RESERVATION_REQUEST_TYPE = 'VoidPreAuthTransactions';
    public const FIRST_DATA_CAPTURE_REQUEST_TYPE = 'PostAuthTransaction';
    public const FIRST_DATA_REFUND_REQUEST_TYPE = 'ReturnTransaction';
    public const FIRST_DATA_AUTHORIZE_SESSION_REQUEST_TYPE = 'AuthorizeSession';
    public const FIRST_DATA_RETURN_REQUEST_TYPE = 'ReturnTransaction';

    /**
     * @uses \SprykerEco\Shared\FirstData\FirstDataConfig::PAYMENT_METHOD_KEY_CREDIT_CARD
     */
    public const PAYMENT_METHOD_KEY_CREDIT_CARD = 'firstDataCreditCard';

    /**
     * @api
     *
     * @param string $requestType
     *
     * @return string
     */
    public function getApiEndpoint(string $requestType): string
    {
        if (in_array($requestType, [static::FIRST_DATA_RESERVATION_REQUEST_TYPE])) {
            return $this->getFirstDataPaymentApiUrl();
        }

        return $this->getFirstDataOrderApiUrl();
    }

    /**
     * @api
     *
     * @return string
     */
    public function geAuthorizeSessionApiEndpoint(): string
    {
        return $this->get(FirstDataConstants::AUTHORIZE_SESSION_API_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPaymentAuthorizationTimeOut(): string
    {
        return '1 hour';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusNew(): string
    {
        return static::OMS_STATUS_NEW;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusAuthorized(): string
    {
        return static::OMS_STATUS_AUTHORIZED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsMaxCaptureRetryCount(): string
    {
        return static::MAX_CAPTURE_RETRY_COUNT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCaptureRequested(): string
    {
        return static::OMS_STATUS_CAPTURE_REQUESTED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCaptured(): string
    {
        return static::OMS_STATUS_CAPTURED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsMaxCancelRetryCount(): string
    {
        return static::MAX_CANCEL_RETRY_COUNT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCancelRequested(): string
    {
        return static::STATE_CANCEL_REQUESTED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCancelRetry(): string
    {
        return static::OMS_STATUS_CANCELLATION_RETRY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCaptureRetry(): string
    {
        return static::OMS_STATUS_CAPTURE_RETRY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCanceled(): string
    {
        return static::OMS_STATUS_CANCELED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusRefunded(): string
    {
        return static::OMS_STATUS_REFUNDED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFirstDataOrderApiUrl(): string
    {
        return $this->get(FirstDataConstants::ORDER_API_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFirstDataPaymentApiUrl(): string
    {
        return $this->get(FirstDataConstants::PAYMENT_API_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFirstDataApiKey(): string
    {
        return $this->get(FirstDataConstants::API_KEY);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFirstDataApiSecret(): string
    {
        return $this->get(FirstDataConstants::API_SECRET);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getHashAlgo(): string
    {
        return $this->get(FirstDataConstants::HASH_ALGO);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStoreName(): string
    {
        return $this->get(FirstDataConstants::STORE_NAME);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStoreId(): string
    {
        return $this->get(FirstDataConstants::STORE_ID);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getAdditionAuthPercentageBuffer(): int
    {
        return $this->get(FirstDataConstants::ADDITIONAL_AUTH_PERCENTAGE_BUFFER, 0);
    }
}
