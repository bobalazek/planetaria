<?php

namespace Application\ControllerProvider\MembersArea;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class DistrictsControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\MembersArea\DistrictsController::indexAction'
        )
        ->bind('members-area.districts');

        $controllers->match(
            '/new',
            'Application\Controller\MembersArea\DistrictsController::newAction'
        )
        ->bind('members-area.districts.new');

        $controllers->match(
            '/{id}/edit',
            'Application\Controller\MembersArea\DistrictsController::editAction'
        )
        ->bind('members-area.districts.edit');

        $controllers->match(
            '/{id}/remove',
            'Application\Controller\MembersArea\DistrictsController::removeAction'
        )
        ->bind('members-area.districts.remove');

        return $controllers;
    }
}
