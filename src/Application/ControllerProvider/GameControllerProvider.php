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
        
        /***** Countries *****/
        $controllers->match(
            '/countries',
            'Application\Controller\Game\CountriesController::indexAction'
        )
        ->bind('game.countries');
        
        $controllers->match(
            '/countries/{id}',
            'Application\Controller\Game\CountriesController::detailAction'
        )
        ->bind('game.countries.detail');
        
        /***** Towns *****/
        $controllers->match(
            '/towns',
            'Application\Controller\Game\TownsController::indexAction'
        )
        ->bind('game.towns');
        
        $controllers->match(
            '/towns/{id}',
            'Application\Controller\Game\TownsController::detailAction'
        )
        ->bind('game.towns.detail');

        return $controllers;
    }
}
