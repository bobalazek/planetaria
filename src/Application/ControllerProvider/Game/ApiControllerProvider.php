<?php

namespace Application\ControllerProvider\Game;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ApiControllerProvider implements ControllerProviderInterface
{
    /**
     * @param Application $app
     *
     * @return \Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->match(
            '/',
            'Application\Controller\Game\ApiController::indexAction'
        )
        ->bind('game.api');

        $controllers->match(
            '/map',
            'Application\Controller\Game\ApiController::mapAction'
        )
        ->bind('game.api.map');

        $controllers->match(
            '/map/{id}',
            'Application\Controller\Game\ApiController::mapDetailAction'
        )
        ->bind('game.api.map.detail');

        return $controllers;
    }
}
