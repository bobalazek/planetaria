<?php

namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Application\Game\Buildings;
use Application\Game\BuildingTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class GameController
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
                'contents/game/index.html.twig'
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function statisticsAction(Application $app)
    {
        $users = $app['orm.em']->getRepository('Application\Entity\UserEntity')->findBy(
            array(),
            array('experiencePoints' => 'DESC'),
            10
        );
        $countries = $app['orm.em']->getRepository('Application\Entity\CountryEntity')->findBy(
            array(),
            array(), // TO-Do
            10
        );
        $towns = $app['orm.em']->getRepository('Application\Entity\TownEntity')->findBy(
            array(),
            array(), // TO-Do
            10
        );

        return new Response(
            $app['twig']->render(
                'contents/game/statistics/index.html.twig',
                array(
                    'users' => $users,
                    'countries' => $countries,
                    'towns' => $towns,
                )
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function premiumAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/game/premium/index.html.twig'
            )
        );
    }
}
