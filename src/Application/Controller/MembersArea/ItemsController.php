<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Application\Game\Weapons;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class WeaponsController
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
                'contents/members-area/weapons/index.html.twig',
                array(
                    'weapons' => Weapons::getAll(),
                )
            )
        );
    }
}
