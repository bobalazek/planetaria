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

        /***** Map *****/
        $controllers->match(
            '/map',
            'Application\Controller\GameController::mapAction'
        )
        ->bind('game.map');
        
        $controllers->match(
            '/map/{id}',
            'Application\Controller\GameController::mapDetailAction'
        )
        ->bind('game.map.detail');
        
        $controllers->match(
            '/map/{id}/build',
            'Application\Controller\GameController::mapBuildAction'
        )
        ->bind('game.map.build');

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

        $controllers->match(
            '/towns/{id}/buildings/{buildingId}',
            'Application\Controller\Game\TownsController::buildingsDetailAction'
        )
        ->bind('game.towns.detail.buildings.detail');

        /***** Users *****/
        $controllers->match(
            '/users',
            'Application\Controller\Game\UsersController::indexAction'
        )
        ->bind('game.users');

        $controllers->match(
            '/users/{id}',
            'Application\Controller\Game\UsersController::detailAction'
        )
        ->bind('game.users.detail');

        return $controllers;
    }
}
