<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Exception;

use Exception;
use SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface;
use Throwable;

class FirstDataGuzzleRequestException extends Exception
{
    /**
     * @var \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface
     */
    protected $response;

    /**
     * @param \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface $response
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        FirstDataGuzzleResponseInterface $response,
        $message = "",
        $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->response = $response;
    }

    /**
     * @return \SprykerEco\Zed\FirstData\Dependency\External\Guzzle\Response\FirstDataGuzzleResponseInterface
     */
    public function getResponse(): FirstDataGuzzleResponseInterface
    {
        return $this->response;
    }
}
