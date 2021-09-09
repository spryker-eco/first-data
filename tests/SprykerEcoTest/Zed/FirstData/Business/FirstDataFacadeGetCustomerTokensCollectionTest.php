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
 * @group FirstDataFacadeGetCustomerTokensCollectionTest
 */
class FirstDataFacadeGetCustomerTokensCollectionTest extends AbstractFirstDataFacadeTest
{
    /**
     * @var \SprykerEcoTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetFirstDataCustomerTokensCollectionMustReturnTokensByCustomer(): void
    {
        //Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $firstDataCustomerToken = $this->tester->haveFirstDataCustomerToken($customerTransfer);

        //Act
        $firstDataCustomerTokensCollectionTransfer = $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock())
            ->getFirstDataCustomerTokensCollection($customerTransfer);

        /** @var \Generated\Shared\Transfer\FirstDataCustomerTokenTransfer $firstDataCustomerTokenTransfer */
        $firstDataCustomerTokenTransfer = $firstDataCustomerTokensCollectionTransfer
            ->getCustomerTokens()->getIterator()->current();

        //Assert
        $this->assertCount(1, $firstDataCustomerTokensCollectionTransfer->getCustomerTokens());
        $this->assertSame($firstDataCustomerToken->getCardToken(), $firstDataCustomerTokenTransfer->getCardToken());
    }
}
