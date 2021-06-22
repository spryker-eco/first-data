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
 * @group FirstDataFacadeAuthorizeSessionResponseTest
 */
class FirstDataFacadeAuthorizeSessionResponseTest extends AbstractFirstDataFacadeTest
{
    protected const TEST_PUBLIC_KEY = 'publicKeyBase64';

    /**
     * @var \SprykerEcoTest\Zed\FirstData\FirstDataBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteAuthorizeSessionResponseMustReturnPublicKeyWithClientTokenAndSaveClientTokenWithCustomerRelation(): void
    {
        //Arrange
        $clientResponse = [
            'publicKeyBase64' => static::TEST_PUBLIC_KEY,
        ];

        $clientResponseHeaders = [
            'Client-Token' => static::TEST_CLIENT_TOKEN,
        ];

        $customerTransfer = $this->tester->haveCustomer();

        //Act
        $firstDataApiResponseTransfer = $this->tester
            ->getFirstDataFacade($this->getFirstDataBusinessFactoryMock($clientResponse, $clientResponseHeaders))
            ->getAuthorizeSessionResponse($customerTransfer);

        $firstDataCardToken = $this->tester->getFirstDataCardTokenWithUserRelationByClientToken(static::TEST_CLIENT_TOKEN);

        /** @var \Orm\Zed\FirstData\Persistence\SpyCusomerToFirstDataCardToken $customerToFirstDataCardTokens */
        $customerToFirstDataCardTokens = $firstDataCardToken->getSpyCusomerToFirstDataCardTokens()->getFirst();

        //Assert
        $this->assertTrue($firstDataApiResponseTransfer->getIsSuccess());
        $this->assertSame(static::TEST_CLIENT_TOKEN, $firstDataApiResponseTransfer->getAuthorizeSessionResponse()->getClientToken());
        $this->assertSame(static::TEST_PUBLIC_KEY, $firstDataApiResponseTransfer->getAuthorizeSessionResponse()->getPublicKeyBase64());
        $this->assertSame($customerTransfer->getCustomerReference(), $customerToFirstDataCardTokens->getCustomerReference());
        $this->assertSame(static::TEST_CLIENT_TOKEN, $firstDataCardToken->getClientToken());
    }
}
