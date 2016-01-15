<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Application\Game\Units;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UnitsController
{
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/members-area/units/index.html.twig',
                array(
                    'units' => Units::getAll(),
                )
            )
        );
    }
}
