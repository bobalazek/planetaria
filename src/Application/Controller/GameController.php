<?php

namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

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
    public function mapAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/game/map/index.html.twig'
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function marketAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/game/market/index.html.twig'
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
        return new Response(
            $app['twig']->render(
                'contents/game/statistics/index.html.twig'
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
