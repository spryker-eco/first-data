<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\FirstData;

use Spryker\Client\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\FirstData\FirstDataConstants;

class FirstDataConfig extends AbstractBundleConfig
{
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
    public function getSharedSecret(): string
    {
        return $this->get(FirstDataConstants::SHARED_SECRET);
    }
}
