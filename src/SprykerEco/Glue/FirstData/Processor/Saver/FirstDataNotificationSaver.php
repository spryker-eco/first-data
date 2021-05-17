<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Processor\Saver;

use Generated\Shared\Transfer\FirstDataNotificationTransfer;
use SprykerEco\Client\FirstData\FirstDataClientInterface;

class FirstDataNotificationSaver implements FirstDataNotificationSaverInterface
{
    protected const KEY_ID_TRANSACTION = 'ipgTransactionId';
    protected const KEY_PAYMENT_TOKEN = 'hosteddataid';
    protected const KEY_STATUS = 'status';
    protected const KEY_TXN_DATE_TIME = 'txndatetime';
    protected const KEY_TXN_TYPE = 'txntype';
    protected const KEY_REF_NUMBER = 'refnumber';
    protected const KEY_ID_SCHEME_TRANSACTION = 'schemeTransactionId';
    protected const KEY_APPROVAL_CODE = 'approval_code';
    protected const KEY_CHARGE_TOTAL = 'chargetotal';
    protected const KEY_CURRENCY = 'currency';
    protected const KEY_ID_ORDER = 'oid';
    protected const KEY_TIMEZONE = 'timezone';

    /**
     * @var \SprykerEco\Client\FirstData\FirstDataClientInterface
     */
    protected $firstDataClient;

    /**
     * @param \SprykerEco\Client\FirstData\FirstDataClientInterface $firstDataClient
     */
    public function __construct(FirstDataClientInterface $firstDataClient)
    {
        $this->firstDataClient = $firstDataClient;
    }

    /**
     * @param array $postData
     *
     * @return void
     */
    public function saveFirstDataNotification(array $postData): void
    {
        $firstDataNotificationRequestTransfer = (new FirstDataNotificationTransfer())
            ->setTransactionId($postData[static::KEY_ID_TRANSACTION] ?? '')
            ->setPaymentToken($postData[static::KEY_PAYMENT_TOKEN] ?? '')
            ->setStatus($postData[static::KEY_STATUS] ?? '')
            ->setTxnDateTime($postData[static::KEY_TXN_DATE_TIME] ?? '')
            ->setTxnType($postData[static::KEY_TXN_TYPE] ?? '')
            ->setRefNumber($postData[static::KEY_REF_NUMBER] ?? '')
            ->setIdSchemeTransaction($postData[static::KEY_ID_SCHEME_TRANSACTION] ?? '')
            ->setApprovalCode($postData[static::KEY_APPROVAL_CODE] ?? '')
            ->setChargeTotal($postData[static::KEY_CHARGE_TOTAL] ?? '')
            ->setCurrency($postData[static::KEY_CURRENCY] ?? '')
            ->setOid($postData[static::KEY_ID_ORDER] ?? '')
            ->setTimezone($postData[static::KEY_TIMEZONE] ?? '');
        $this->firstDataClient->processNotification($firstDataNotificationRequestTransfer);
    }
}
