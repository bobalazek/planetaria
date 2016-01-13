<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class CountriesController
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
                'contents/game/countries/index.html.twig'
            )
        );
    }
}
