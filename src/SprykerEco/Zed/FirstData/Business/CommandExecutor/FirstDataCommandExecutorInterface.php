<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\FirstData\Business\CommandExecutor;

use Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer;

interface FirstDataCommandExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer
     *
     * @return void
     */
    public function executeOmsCommand(FirstDataOmsCommandRequestTransfer $firstDataOmsCommandRequestTransfer): void;
}
