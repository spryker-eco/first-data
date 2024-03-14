<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\FirstData;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FirstDataApiResponseTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer;
use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
use Generated\Shared\Transfer\FirstDataHashRequestTransfer;
use Generated\Shared\Transfer\FirstDataNotificationTransfer;

interface FirstDataClientInterface
{
    /**
     * Specification:
     * - Sorts params from FirstDataHashRequestTransfer in asc order and removes null values.
     * - Generates HMAC hash from FirstDataHashRequestTransfer params.
     * - Returns base64 encoded hash.
     * - Throws an exception if params are not provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataHashRequestTransfer $firstDataHashRequestTransfer
     *
     * @throws \SprykerEco\Client\FirstData\Exception\InvalidArgumentsNumberProvided
     *
     * @return string
     */
    public function generateHash(FirstDataHashRequestTransfer $firstDataHashRequestTransfer): string;

    /**
     * Specification:
     * - Saves first data notification to database.
     * - Makes Zed request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataNotificationTransfer $firstDataNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataNotificationTransfer
     */
    public function processNotification(FirstDataNotificationTransfer $firstDataNotificationTransfer): FirstDataNotificationTransfer;

    /**
     * Specification:
     * - Retrieves token by `ClientToken` from database.
     * - Saves first data payment token data to database.
     * - Makes Zed request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer
     */
    public function processTokenization(FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer): FirstDataCustomerTokenTransfer;

    /**
     * Specification:
     * - Makes API call to the First Data in order to receive authorize session data `ClientToken` and `PublicKeyBase64`.
     * - Associate `ClientToken` with current customer save association to database.
     * - Makes Zed request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataApiResponseTransfer
     */
    public function getAuthorizeSessionResponse(CustomerTransfer $customerTransfer): FirstDataApiResponseTransfer;

    /**
     * Specification:
     * - Reads all available card tokens from DB for given customer.
     * - Makes Zed request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\FirstDataCustomerTokensCollectionTransfer
     */
    public function getCustomerTokensCollection(CustomerTransfer $customerTransfer): FirstDataCustomerTokensCollectionTransfer;
}
