<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\FirstData\Generator;

use Generated\Shared\Transfer\FirstDataHashRequestTransfer;
use SprykerEco\Client\FirstData\Exception\InvalidArgumentsNumberProvided;
use SprykerEco\Client\FirstData\FirstDataConfig;

class HashGenerator implements HashGeneratorInterface
{
    protected const SEPARATOR = '|';

    /**
     * @var \SprykerEco\Client\FirstData\FirstDataConfig
     */
    protected $firstDataConfig;

    /**
     * @param \SprykerEco\Client\FirstData\FirstDataConfig $firstDataConfig
     */
    public function __construct(FirstDataConfig $firstDataConfig)
    {
        $this->firstDataConfig = $firstDataConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\FirstDataHashRequestTransfer $firstDataHashRequestTransfer
     *
     * @throws \SprykerEco\Client\FirstData\Exception\InvalidArgumentsNumberProvided
     *
     * @return string
     */
    public function generateHash(FirstDataHashRequestTransfer $firstDataHashRequestTransfer): string
    {
        $firstDataHashRequestParams = $firstDataHashRequestTransfer->toArray();

        ksort($firstDataHashRequestParams);

        $firstDataHashRequestParams = array_filter($firstDataHashRequestParams, function ($arrayValue) {
            return $arrayValue !== null;
        });

        if (empty($firstDataHashRequestParams)) {
            throw new InvalidArgumentsNumberProvided('At least 1 param must be provided.');
        }

        $params = [];

        foreach ($firstDataHashRequestParams as $param) {
            if (is_bool($param)) {
                $param = $param ? 'true' : 'false';
            }

            $params[] = $param;
        }

        $paramString = implode(static::SEPARATOR, $params);

        $hashExtended = hash_hmac(
            $this->firstDataConfig->getHashAlgo(),
            $paramString,
            $this->firstDataConfig->getSharedSecret()
        );

        return base64_encode($hashExtended);
    }
}
