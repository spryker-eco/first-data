<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\FirstData;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class FirstDataConfig extends AbstractBundleConfig
{
    public const PROVIDER_NAME = 'FirstData';
    public const PAYMENT_METHOD_KEY_CREDIT_CARD = 'firstDataCreditCard';
    public const PAYMENT_PROVIDER_NAME_KEY = 'firstData';
    public const METHOD_NAME_CREDIT_CARD = 'FirstData Credit Card';

    public const SHA256 = 'SHA256';
    public const SHA384 = 'SHA384';
    public const SHA512 = 'SHA512';
    public const HMACSHA256 = 'HMACSHA256';
}
