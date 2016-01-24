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
        ->bind('game.towns.buildings.detail');

        $controllers->match(
            '/towns/{id}/buildings/{buildingId}/upgrade',
            'Application\Controller\Game\TownsController::buildingsUpgradeAction'
        )
        ->bind('game.towns.buildings.upgrade');

        $controllers->match(
            '/towns/{id}/buildings/{buildingId}/remove',
            'Application\Controller\Game\TownsController::buildingsRemoveAction'
        )
        ->bind('game.towns.buildings.remove');

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

        /***** My *****/
        $controllers->match(
            '/my',
            'Application\Controller\Game\MyController::indexAction'
        )
        ->bind('game.my');

        $controllers->match(
            '/my/profile',
            'Application\Controller\Game\MyController::profileAction'
        )
        ->bind('game.my.profile');

        $controllers->match(
            '/my/settings',
            'Application\Controller\Game\MyController::settingsAction'
        )
        ->bind('game.my.settings');

        $controllers->match(
            '/my/password',
            'Application\Controller\Game\MyController::passwordAction'
        )
        ->bind('game.my.password');

        /* Messages */
        $controllers->match(
            '/my/messages',
            'Application\Controller\Game\MyController::messagesAction'
        )
        ->bind('game.my.messages');
        
        $controllers->match(
            '/my/messages/new',
            'Application\Controller\Game\MyController::messagesNewAction'
        )
        ->bind('game.my.messages.new');
        
        $controllers->match(
            '/my/messages/{id}',
            'Application\Controller\Game\MyController::messagesDetailAction'
        )
        ->bind('game.my.messages.detail');
        
        $controllers->match(
            '/my/messages/{id}/reply',
            'Application\Controller\Game\MyController::messagesReplyAction'
        )
        ->bind('game.my.messages.reply');
        
        /* Notifications */
        $controllers->match(
            '/my/notifications',
            'Application\Controller\Game\MyController::notificationsAction'
        )
        ->bind('game.my.notifications');
        
        $controllers->match(
            '/my/notifications/{id}',
            'Application\Controller\Game\MyController::notificationsDetailAction'
        )
        ->bind('game.my.notifications.detail');
        
        $controllers->match(
            '/my/notifications/{id}/acknowledge',
            'Application\Controller\Game\MyController::notificationsAcknowledgeAction'
        )
        ->bind('game.my.notifications.acknowledge');

        return $controllers;
    }
}
