<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Generator;

interface HashGeneratorInterface
{
    /**
     * @param array $params
     *
     * @return string
     */
    public function generateHash(array $params): string;
}
