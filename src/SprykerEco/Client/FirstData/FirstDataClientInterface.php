<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\FirstData;

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
}
