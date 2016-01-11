<?php

namespace Application\ControllerProvider;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class GameControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\GameController::indexAction'
        )
        ->bind('game');
        
        $controllers->match(
            '/map',
            'Application\Controller\GameController::mapAction'
        )
        ->bind('game.map');
        
        $controllers->match(
            '/market',
            'Application\Controller\GameController::marketAction'
        )
        ->bind('game.market');
        
        $controllers->match(
            '/statistics',
            'Application\Controller\GameController::statisticsAction'
        )
        ->bind('game.statistics');
        
        $controllers->match(
            '/premium',
            'Application\Controller\GameController::premiumAction'
        )
        ->bind('game.premium');

        return $controllers;
    }
}
