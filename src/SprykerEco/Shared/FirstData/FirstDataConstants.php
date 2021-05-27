<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\FirstData;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface FirstDataConstants
{
    /**
     * Specification:
     * - Contains an algorithm for hash function.
     *
     * @api
     */
    public const HASH_ALGO = 'FIRST_DATA:HASH_ALGO';

    /**
     * Specification:
     * - Contains an algorithm for hash function.
     *
     * @api
     */
    public const HMACHASH_ALGO = 'FIRST_DATA:HMACHASH_ALGO';

    /**
     * Specification:
     * - Contains shared secret used for HMAC hashing.
     *
     * @api
     */
    public const SHARED_SECRET = 'FIRST_DATA:SHARED_SECRET';

    /**
     * Specification:
     * - Contains an url for orders FirstData API.
     *
     * @api
     */
    public const ORDER_API_URL = 'FIRST_DATA:ORDER_API_URL';

    /**
     * Specification:
     * - Contains an url for authorize session FirstData API, needed for paymentJs integration.
     *
     * @api
     */
    public const AUTHORIZE_SESSION_API_URL = 'FIRST_DATA:AUTHORIZE_SESSION_API_URL';

    /**
     * Specification:
     * - Contains an url for payments FirstData API.
     *
     * @api
     */
    public const PAYMENT_API_URL = 'FIRST_DATA:PAYMENT_API_URL';

    /**
     * Specification:
     * - Contains a key to access FirstData API.
     *
     * @api
     */
    public const API_KEY = 'FIRST_DATA:API_KEY';

    /**
     * Specification:
     * - Contains a secret key to access FirstData API.
     *
     * @api
     */
    public const API_SECRET = 'FIRST_DATA:API_SECRET';

    /**
     * Specification:
     * - Represents Store ID configuration required for Hosted Page.
     *
     * @api
     */
    public const STORE_NAME = 'FIRST_DATA:STORE_NAME';

    /**
     * Specification:
     * - Represents Store ID configuration required for Hosted Page.
     *
     * @api
     */
    public const STORE_ID = 'FIRST_DATA:STORE_ID';

    /**
     * Specification:
     * - Represents percentage value to be authorised in addition to the order total.
     *
     * @api
     */
    public const ADDITIONAL_AUTH_PERCENTAGE_BUFFER = 'FIRST_DATA:ADDITIONAL_AUTH_PERCENTAGE_BUFFER';

    /**
     * Specification:
     * - Represents url that customer will be redirected from Hosted Page if authorization is failed.
     *
     * @api
     */
    public const RESPONSE_FAIL_URL = 'FIRST_DATA:RESPONSE_FAIL_URL';

    /**
     * Specification:
     * - Represents url that customer will be redirected from Hosted Page if authorization is success.
     *
     * @api
     */
    public const RESPONSE_SUCCESS_URL = 'FIRST_DATA:RESPONSE_SUCCESS_URL';

    /**
     * Specification:
     * - Represents url used for transaction callbacks for server-to-server communication.
     *
     * @api
     */
    public const TRANSACTION_NOTIFICATION_URL = 'FIRST_DATA:TRANSACTION_NOTIFICATION_URL';

    /**
     * Specification:
     * - Represents url used for payment processing.
     *
     * @api
     */
    public const FIRST_DATA_PAYMENT_PROCESSING_LINK = 'FIRST_DATA:PAYMENT_PROCESSING_LINK';
}
