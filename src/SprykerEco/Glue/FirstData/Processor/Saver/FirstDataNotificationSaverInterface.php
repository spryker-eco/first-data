<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Processor\Saver;

interface FirstDataNotificationSaverInterface
{
    /**
     * @param array $postData
     *
     * @return void
     */
    public function saveFirstDataNotification(array $postData): void;
}
