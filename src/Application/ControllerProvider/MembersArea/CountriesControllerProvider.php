<?php

namespace Application\ControllerProvider\MembersArea;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class CountriesControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\MembersArea\CountriesController::indexAction'
        )
        ->bind('members-area.countries');

        $controllers->match(
            '/new',
            'Application\Controller\MembersArea\CountriesController::newAction'
        )
        ->bind('members-area.countries.new');

        $controllers->match(
            '/{id}/edit',
            'Application\Controller\MembersArea\CountriesController::editAction'
        )
        ->bind('members-area.countries.edit');

        $controllers->match(
            '/{id}/remove',
            'Application\Controller\MembersArea\CountriesController::removeAction'
        )
        ->bind('members-area.countries.remove');

        return $controllers;
    }
}
