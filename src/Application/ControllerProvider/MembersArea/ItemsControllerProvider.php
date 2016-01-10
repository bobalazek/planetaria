<?php

namespace Application\ControllerProvider\MembersArea;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ItemsControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\MembersArea\ItemsController::indexAction'
        )
        ->bind('members-area.items');

        $controllers->match(
            '/new',
            'Application\Controller\MembersArea\ItemsController::newAction'
        )
        ->bind('members-area.items.new');

        $controllers->match(
            '/{id}/edit',
            'Application\Controller\MembersArea\ItemsController::editAction'
        )
        ->bind('members-area.items.edit');

        $controllers->match(
            '/{id}/remove',
            'Application\Controller\MembersArea\ItemsController::removeAction'
        )
        ->bind('members-area.items.remove');

        return $controllers;
    }
}
