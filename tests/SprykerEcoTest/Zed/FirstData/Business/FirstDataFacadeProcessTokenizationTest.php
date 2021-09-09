<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\FirstData\Business;

use Generated\Shared\Transfer\FirstDataCustomerTokenTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerEcoTest
 * @group Zed
 * @group FirstData
 * @group Business
 * @group Facade
 * @group FirstDataFacadeProcessTokenizationTest
 */
class FirstDataFacadeProcessTokenizationTest extends AbstractFirstDataFacadeTest
{
    protected const TEST_CUSTOMER_TOKEN = 'test_customer_token';
    protected const TEST_EXP_MONTH = 'test_exp_month';
    protected const TEST_EXP_YEAR = 'test_exp_year';

    /**
     * @var \SprykerEcoTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProcessTokenizationMustUpdateCustomerTokenDataByClientToken(): void
    {
        //Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $firstDataCustomerToken = $this->tester->haveFirstDataCustomerToken($customerTransfer);

        //Act
        $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock())
            ->processTokenization(
                (new FirstDataCustomerTokenTransfer())
                    ->setClientToken($firstDataCustomerToken->getClientToken())
                    ->setCardToken(static::TEST_CUSTOMER_TOKEN)
                    ->setExpMonth(static::TEST_EXP_MONTH)
                    ->setExpYear(static::TEST_EXP_YEAR)
            );

        $firstDataCardTokenTokenized = $this->tester->getFirstDataCardTokenWithUserRelationByClientToken(
            $firstDataCustomerToken->getClientToken()
        );

        //Assert
        $this->assertSame(static::TEST_CUSTOMER_TOKEN, $firstDataCardTokenTokenized->getCardToken());
        $this->assertSame(static::TEST_EXP_MONTH, $firstDataCardTokenTokenized->getExpMonth());
        $this->assertSame(static::TEST_EXP_YEAR, $firstDataCardTokenTokenized->getExpYear());
    }
}
