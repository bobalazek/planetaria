<?php

namespace Application\ControllerProvider\MembersArea;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class PlanetsControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\MembersArea\PlanetsController::indexAction'
        )
        ->bind('members-area.planets');

        $controllers->match(
            '/new',
            'Application\Controller\MembersArea\PlanetsController::newAction'
        )
        ->bind('members-area.planets.new');

        $controllers->match(
            '/{id}/edit',
            'Application\Controller\MembersArea\PlanetsController::editAction'
        )
        ->bind('members-area.planets.edit');

        $controllers->match(
            '/{id}/remove',
            'Application\Controller\MembersArea\PlanetsController::removeAction'
        )
        ->bind('members-area.planets.remove');

        return $controllers;
    }
}
