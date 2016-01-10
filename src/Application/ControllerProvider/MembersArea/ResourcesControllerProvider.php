<?php

namespace Application\ControllerProvider\MembersArea;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ResourcesControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\MembersArea\ResourcesController::indexAction'
        )
        ->bind('members-area.resources');

        $controllers->match(
            '/new',
            'Application\Controller\MembersArea\ResourcesController::newAction'
        )
        ->bind('members-area.resources.new');

        $controllers->match(
            '/{id}/edit',
            'Application\Controller\MembersArea\ResourcesController::editAction'
        )
        ->bind('members-area.resources.edit');

        $controllers->match(
            '/{id}/remove',
            'Application\Controller\MembersArea\ResourcesController::removeAction'
        )
        ->bind('members-area.resources.remove');

        return $controllers;
    }
}
