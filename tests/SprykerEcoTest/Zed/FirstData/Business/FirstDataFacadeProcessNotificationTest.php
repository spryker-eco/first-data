<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\FirstData\Business;

use Generated\Shared\Transfer\FirstDataNotificationTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerEcoTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeProcessNotificationTest
 */
class FirstDataFacadeProcessNotificationTest extends AbstractFirstDataFacadeTest
{
    /**
     * @var \SprykerEcoTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProcessNotificationMustSaveNotification(): void
    {
        //Arrange
        $firstDataNotificationTransfer = new FirstDataNotificationTransfer();
        $firstDataNotificationTransfer->setStatus('APPROVED');
        $firstDataNotificationTransfer->setRefNumber('84557082705');
        $firstDataNotificationTransfer->setTransactionId('84557082705');
        $firstDataNotificationTransfer->setIdSchemeTransaction('0301MCC392572');
        $firstDataNotificationTransfer->setOid('C-70168565-2d7d-4f4b-aad3-be82e156ae13');
        $firstDataNotificationTransfer->setApprovalCode('Y3AOK54423A45570827053APPXX3A854987');
        $firstDataNotificationTransfer->setPaymentToken('B5912093-61AB-497D-BA8F-1B10868351FE');
        $firstDataNotificationTransfer->setChargeTotal('0.00');
        $firstDataNotificationTransfer->setTxnType('preauth');
        $firstDataNotificationTransfer->setTxnDateTime('2021-03-01-14:37:45');
        $firstDataNotificationTransfer->setTimezone('UTC');
        $firstDataNotificationTransfer->setCurrency('840');

        //Act
        $response = $this->tester->getFirstDataFacade($this->getFirstDataBusinessFactoryMock())->processNotification($firstDataNotificationTransfer);

        //Assert
        $this->assertEquals($firstDataNotificationTransfer->getStatus(), $response->getStatus());
        $this->assertEquals($firstDataNotificationTransfer->getRefNumber(), $response->getRefNumber());
        $this->assertEquals($firstDataNotificationTransfer->getTransactionId(), $response->getTransactionId());
        $this->assertEquals($firstDataNotificationTransfer->getIdSchemeTransaction(), $response->getIdSchemeTransaction());
        $this->assertEquals($firstDataNotificationTransfer->getOid(), $response->getOid());
        $this->assertEquals($firstDataNotificationTransfer->getApprovalCode(), $response->getApprovalCode());
        $this->assertEquals($firstDataNotificationTransfer->getPaymentToken(), $response->getPaymentToken());
        $this->assertEquals($firstDataNotificationTransfer->getChargeTotal(), $response->getChargeTotal());
        $this->assertEquals($firstDataNotificationTransfer->getTxnType(), $response->getTxnType());
        $this->assertEquals($firstDataNotificationTransfer->getTxnDateTime(), $response->getTxnDateTime());
        $this->assertEquals($firstDataNotificationTransfer->getTimezone(), $response->getTimezone());
        $this->assertEquals($firstDataNotificationTransfer->getCurrency(), $response->getCurrency());
    }
}
