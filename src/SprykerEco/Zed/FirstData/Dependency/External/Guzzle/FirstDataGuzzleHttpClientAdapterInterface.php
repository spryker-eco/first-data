<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Dependency\External\Guzzle;

use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface;

interface FirstDataGuzzleHttpClientAdapterInterface
{
    /**
     * @param string $url
     * @param array $headers
     * @param string $body
     *
     * @throws \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Exception\FirstDataGuzzleRequestException
     *
     * @return \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface
     */
    public function post(string $url, array $headers = [], string $body = ''): FirstDataGuzzleResponseInterface;
}
