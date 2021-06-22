<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\FirstData\Business;

/**
 * Auto-generated group annotations
 *
 * @group SprykerEcoTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeCheckIfApprovedNotificationReceivedTest
 */
class FirstDataFacadeCheckIfApprovedNotificationReceivedTest extends AbstractFirstDataFacadeTest
{
    protected const TEST_TRANSACTION_ID = 'test_transaction_id';
    protected const TEST_OID = 'test_oid';
    protected const APPROVED_STATUS = 'APPROVED';
    protected const DUMMY_STATUS = 'DUMMY';

    /**
     * @var \SprykerEcoTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCheckIfApprovedNotificationReceivedMustReturnTrueWhenApprovedStatusReceived(): void
    {
        //Arrange
        $paymentFirstDataNotificationEntity = $this->tester->createTestPaymentFirstDataNotificationEntity(
            static::TEST_TRANSACTION_ID,
            static::TEST_OID,
            static::APPROVED_STATUS
        );

        //Act
        $isApprovedNotificationReceived = $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock())
            ->checkIfApprovedNotificationReceived(static::TEST_TRANSACTION_ID);

        //Assert
        $this->assertTrue($isApprovedNotificationReceived);
    }

    /**
     * @return void
     */
    public function testCheckIfApprovedNotificationReceivedMustReturnFalseWhenDummyStatusReceived(): void
    {
        //Arrange
        $paymentFirstDataNotificationEntity = $this->tester->createTestPaymentFirstDataNotificationEntity(
            static::TEST_TRANSACTION_ID,
            static::TEST_OID,
            static::DUMMY_STATUS
        );

        //Act
        $isApprovedNotificationReceived = $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock())
            ->checkIfApprovedNotificationReceived(static::TEST_TRANSACTION_ID);

        //Assert
        $this->assertFalse($isApprovedNotificationReceived);
    }

    /**
     * @return void
     */
    public function testCheckIfApprovedNotificationReceivedMustReturnFalseWhenNoNotification(): void
    {
        //Act
        $isApprovedNotificationReceived = $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock())
            ->checkIfApprovedNotificationReceived(static::TEST_TRANSACTION_ID);

        //Assert
        $this->assertFalse($isApprovedNotificationReceived);
    }
}
