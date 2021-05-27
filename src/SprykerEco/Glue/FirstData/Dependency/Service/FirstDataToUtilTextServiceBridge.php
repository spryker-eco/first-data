<?php
//phpcs:ignoreFile
/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Dependency\Service;

class FirstDataToUtilTextServiceBridge implements FirstDataToUtilTextServiceInterface
{
    /**
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct($utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length)
    {
        return $this->utilTextService->generateRandomString($length);
    }
}
