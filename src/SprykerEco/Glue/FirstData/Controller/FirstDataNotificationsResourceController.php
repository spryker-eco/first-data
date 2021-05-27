<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Glue\FirstData\Controller;

use Spryker\Glue\Kernel\Controller\FormattedAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Glue\FirstData\FirstDataFactory getFactory()
 */
class FirstDataNotificationsResourceController extends FormattedAbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $httpRequest): Response
    {
        $this->getFactory()
            ->createFirstDataNotificationSaver()
            ->saveFirstDataNotification($httpRequest->request->all());

        return new Response('', Response::HTTP_OK);
    }
}
