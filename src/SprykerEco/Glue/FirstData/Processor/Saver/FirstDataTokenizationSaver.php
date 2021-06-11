<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Processor\Saver;

use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;
use SprykerEco\Client\FirstData\FirstDataClientInterface;
use SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface;

class FirstDataTokenizationSaver implements FirstDataTokenizationSaverInterface
{
    protected const KEY_CARD = 'card';
    protected const KEY_CARD_BIN = 'bin';
    protected const KEY_CARD_BRAND = 'brand';
    protected const KEY_CARD_EXP = 'exp';
    protected const KEY_CARD_EXP_YEAR = 'year';
    protected const KEY_CARD_EXP_MONTH = 'month';
    protected const KEY_CARD_LAST4 = 'last4';
    protected const KEY_CARD_NAME = 'name';
    protected const KEY_CARD_TOKEN = 'token';
    protected const KEY_CARD_MASKED = 'masked';
    protected const KEY_CARD_ADDRESS_1 = 'address1';
    protected const KEY_CARD_ADDRESS_2 = 'address2';
    protected const KEY_CARD_CITY = 'city';
    protected const KEY_CARD_REGION = 'region';
    protected const KEY_CARD_COUNTRY = 'country';
    protected const KEY_CARD_POSTAL_CODE = 'postalCode';

    /**
     * @var \SprykerEco\Client\FirstData\FirstDataClientInterface
     */
    protected $firstDataClient;

    /**
     * @var \SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface
     */
    private $encodingService;

    /**
     * @param \SprykerEco\Client\FirstData\FirstDataClientInterface $firstDataClient
     * @param \SprykerEco\Glue\FirstData\Dependency\Service\FirstDataToUtilEncodingServiceInterface $encodingService
     */
    public function __construct(
        FirstDataClientInterface $firstDataClient,
        FirstDataToUtilEncodingServiceInterface $encodingService
    ) {
        $this->firstDataClient = $firstDataClient;
        $this->encodingService = $encodingService;
    }

    /**
     * @param string $jsonPostData
     * @param string $clientToken
     *
     * @return void
     */
    public function saveFirstDataToken(string $jsonPostData, string $clientToken): void
    {
        $postData = $this->encodingService->decodeJson($jsonPostData, true);

        $firstDataCustomerTokenTransfer = (new FirstDataCustomerTokenTransfer())
            ->setClientToken($clientToken)
            ->setCardToken($postData[static::KEY_CARD][static::KEY_CARD_TOKEN] ?? '')
            ->setMasked($postData[static::KEY_CARD][static::KEY_CARD_MASKED] ?? '')
            ->setName($postData[static::KEY_CARD][static::KEY_CARD_NAME] ?? '')
            ->setLast4($postData[static::KEY_CARD][static::KEY_CARD_LAST4] ?? '')
            ->setAddress1($postData[static::KEY_CARD][static::KEY_CARD_ADDRESS_1] ?? '')
            ->setAddress2($postData[static::KEY_CARD][static::KEY_CARD_ADDRESS_2] ?? '')
            ->setCity($postData[static::KEY_CARD][static::KEY_CARD_CITY] ?? '')
            ->setRegion($postData[static::KEY_CARD][static::KEY_CARD_REGION] ?? '')
            ->setCountry($postData[static::KEY_CARD][static::KEY_CARD_COUNTRY] ?? '')
            ->setPostalCode($postData[static::KEY_CARD][static::KEY_CARD_POSTAL_CODE] ?? '')
            ->setBin($postData[static::KEY_CARD][static::KEY_CARD_BIN] ?? '')
            ->setExpYear($postData[static::KEY_CARD][static::KEY_CARD_EXP][static::KEY_CARD_EXP_YEAR] ?? '')
            ->setExpMonth($postData[static::KEY_CARD][static::KEY_CARD_EXP][static::KEY_CARD_EXP_MONTH] ?? '')
            ->setBrand($postData[static::KEY_CARD][static::KEY_CARD_BRAND] ?? '');

        $this->firstDataClient->processTokenization($firstDataCustomerTokenTransfer);
    }
}
