<?php

namespace Application\ControllerProvider\MembersArea;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class BuildingsControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\MembersArea\BuildingsController::indexAction'
        )
        ->bind('members-area.buildings');

        $controllers->match(
            '/new',
            'Application\Controller\MembersArea\BuildingsController::newAction'
        )
        ->bind('members-area.buildings.new');

        $controllers->match(
            '/{id}/edit',
            'Application\Controller\MembersArea\BuildingsController::editAction'
        )
        ->bind('members-area.buildings.edit');

        $controllers->match(
            '/{id}/remove',
            'Application\Controller\MembersArea\BuildingsController::removeAction'
        )
        ->bind('members-area.buildings.remove');

        return $controllers;
    }
}
