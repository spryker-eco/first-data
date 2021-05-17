<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\FirstData\Generator;

use Generated\Shared\Transfer\FirstDataHashRequestTransfer;

interface HashGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstDataHashRequestTransfer $firstDataHashRequestTransfer
     *
     * @throws \SprykerEco\Client\FirstData\Exception\InvalidArgumentsNumberProvided
     *
     * @return string
     */
    public function generateHash(FirstDataHashRequestTransfer $firstDataHashRequestTransfer): string;
}
