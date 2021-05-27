<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\Api\Generator;

use SprykerEco\Zed\FirstData\FirstDataConfig;

class HashGenerator implements HashGeneratorInterface
{
    /**
     * @var \SprykerEco\Zed\FirstData\FirstDataConfig;
     */
    protected $firstDataConfig;

    /**
     * @param \SprykerEco\Zed\FirstData\FirstDataConfig $firstDataConfig
     */
    public function __construct(FirstDataConfig $firstDataConfig)
    {
        $this->firstDataConfig = $firstDataConfig;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function generateHash(array $params): string
    {
        $messageSignatureContent = implode('', $params);
        $hmac = hash_hmac(
            $this->firstDataConfig->getHashAlgo(),
            $messageSignatureContent,
            $this->firstDataConfig->getFirstDataApiSecret()
        );

        return base64_encode($hmac);
    }
}
