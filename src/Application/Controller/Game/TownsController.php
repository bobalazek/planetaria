<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownsController
{
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Application $app)
    {
        $towns = $app['orm.em']
            ->getRepository('Application\Entity\TownEntity')
            ->findAll()
        ;

        return new Response(
            $app['twig']->render(
                'contents/game/towns/index.html.twig',
                array(
                    'towns' => $towns,
                )
            )
        );
    }

    /**
     * @param integer     $id
     * @param Application $app
     *
     * @return Response
     */
    public function detailAction($id, Application $app)
    {
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            $app->abort(404);
        }

        return new Response(
            $app['twig']->render(
                'contents/game/towns/detail.html.twig',
                array(
                    'town' => $town,
                )
            )
        );
    }
}