<?php

namespace Application\ControllerProvider;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class IndexControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\IndexController::indexAction'
        )
        ->bind('index');

        $controllers->match(
            '/terms',
            'Application\Controller\IndexController::termsAction'
        )
        ->bind('terms');

        $controllers->match(
            '/privacy',
            'Application\Controller\IndexController::privacyAction'
        )
        ->bind('privacy');

        $controllers->match(
            '/contact-us',
            'Application\Controller\IndexController::contactUsAction'
        )
        ->bind('contact-us');

        return $controllers;
    }
}
