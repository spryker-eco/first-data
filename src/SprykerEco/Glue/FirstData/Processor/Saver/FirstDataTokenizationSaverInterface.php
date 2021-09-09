<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Processor\Saver;

interface FirstDataTokenizationSaverInterface
{
    /**
     * @param string $jsonPostData
     * @param string $clientToken
     *
     * @return void
     */
    public function saveFirstDataToken(string $jsonPostData, string $clientToken): void;
}
