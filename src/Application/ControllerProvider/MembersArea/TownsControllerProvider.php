<?php

namespace Application\ControllerProvider\MembersArea;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownsControllerProvider implements ControllerProviderInterface
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
            '',
            'Application\Controller\MembersArea\TownsController::indexAction'
        )
        ->bind('members-area.towns');

        $controllers->match(
            '/new',
            'Application\Controller\MembersArea\TownsController::newAction'
        )
        ->bind('members-area.towns.new');

        $controllers->match(
            '/{id}/edit',
            'Application\Controller\MembersArea\TownsController::editAction'
        )
        ->bind('members-area.towns.edit');

        $controllers->match(
            '/{id}/remove',
            'Application\Controller\MembersArea\TownsController::removeAction'
        )
        ->bind('members-area.towns.remove');

        return $controllers;
    }
}
