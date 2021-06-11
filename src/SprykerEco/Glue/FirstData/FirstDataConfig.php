<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\FirstData\FirstDataConfig as SharedFirstDataConfig;
use SprykerEco\Shared\FirstData\FirstDataConstants;

class FirstDataConfig extends AbstractBundleConfig
{
    public const RESPONSE_CODE_INVALID_FIRST_DATA_PAYMENT_OID = '1109';
    public const RESPONSE_CODE_INVALID_FIRST_DATA_PAYMENT_TRANSACTION_ID = '1110';

    public const RESPONSE_DETAILS_FIRST_DATA_PAYMENT_TRANSACTION_ID_MISSING = 'firstDataTransactionData.transaction_id field is missing.';
    public const RESPONSE_DETAILS_FIRST_DATA_PAYMENT_OID_MISSING = 'firstDataTransactionData.oid field is missing.';

    public const RESOURCE_FIRST_DATA_NOTIFICATIONS = 'first-data-notifications';
    public const RESOURCE_FIRST_DATA_TOKENIZATION = 'first-data-tokenization';
    public const RESOURCE_FIRST_DATA_AUTHORIZE_SESSION = 'first-data-authorize-session';

    protected const IS_3D_SECURE = false;
    protected const MOBILE_MODE = false;
    protected const ASSIGN_TOKEN = true;
    protected const DECLINE_HOSTED_DATA_DUPLICATES = false;
    protected const TIMEZONE = 'Etc/UTC';
    protected const CHECKOUT_OPTION = 'combinedpage';
    protected const LANGUAGE = 'en_US';
    protected const TOKEN_TYPE = 'MULTIPAY';
    protected const TRANSACTION_TYPE = 'preauth';
    protected const TRANSACTION_DATE_TIME_FORMAT = 'Y:m:d-H:i:s';
    protected const CURRENCY_ISO_NUMBERS = [
        'USD' => '840',
        'EUR' => '978',
    ];

    /**
     * @api
     *
     * @return string
     */
    public function getHmacHashAlgo(): string
    {
        return $this->get(FirstDataConstants::HMACHASH_ALGO);
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
    public function getResponseFailUrl(): string
    {
        return $this->get(FirstDataConstants::RESPONSE_FAIL_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResponseSuccessUrl(): string
    {
        return $this->get(FirstDataConstants::RESPONSE_SUCCESS_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTransactionNotificationUrl(): string
    {
        return $this->get(FirstDataConstants::TRANSACTION_NOTIFICATION_URL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMethodNameCreditCard(): string
    {
        return SharedFirstDataConfig::PAYMENT_METHOD_KEY_CREDIT_CARD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTransactionType(): string
    {
        return static::TRANSACTION_TYPE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCheckoutOption(): string
    {
        return static::CHECKOUT_OPTION;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDateTimeFormat(): string
    {
        return static::TRANSACTION_DATE_TIME_FORMAT;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getIs3dSecure(): bool
    {
        return static::IS_3D_SECURE;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getMobileMode(): bool
    {
        return static::MOBILE_MODE;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getAssignToken(): bool
    {
        return static::ASSIGN_TOKEN;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getDeclineHostedDataDuplicates(): bool
    {
        return static::DECLINE_HOSTED_DATA_DUPLICATES;
    }

    /**
     * @api
     *
     * @param string $code
     *
     * @return string
     */
    public function getIsoNumberByCode(string $code): string
    {
        return static::CURRENCY_ISO_NUMBERS[$code];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTimezone(): string
    {
        return static::TIMEZONE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return static::LANGUAGE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTokenType(): string
    {
        return static::TOKEN_TYPE;
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

    /**
     * @api
     *
     * @return string
     */
    public function getFirstDataPaymentProcessingLink(): string
    {
        return $this->get(FirstDataConstants::FIRST_DATA_PAYMENT_PROCESSING_LINK);
    }
}
