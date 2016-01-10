<?php

namespace Application\ControllerProvider\MembersArea;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UnitsControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\MembersArea\UnitsController::indexAction'
        )
        ->bind('members-area.units');

        $controllers->match(
            '/new',
            'Application\Controller\MembersArea\UnitsController::newAction'
        )
        ->bind('members-area.units.new');

        $controllers->match(
            '/{id}/edit',
            'Application\Controller\MembersArea\UnitsController::editAction'
        )
        ->bind('members-area.units.edit');

        $controllers->match(
            '/{id}/remove',
            'Application\Controller\MembersArea\UnitsController::removeAction'
        )
        ->bind('members-area.units.remove');

        return $controllers;
    }
}
