<?php
/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Dependency\Service;

interface FirstDataToUtilTextServiceInterface
{
    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length);
}
