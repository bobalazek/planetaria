<?php

namespace Application\ControllerProvider\MembersArea;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class SkillsControllerProvider implements ControllerProviderInterface
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
            'Application\Controller\MembersArea\SkillsController::indexAction'
        )
        ->bind('members-area.skills');

        $controllers->match(
            '/new',
            'Application\Controller\MembersArea\SkillsController::newAction'
        )
        ->bind('members-area.skills.new');

        $controllers->match(
            '/{id}/edit',
            'Application\Controller\MembersArea\SkillsController::editAction'
        )
        ->bind('members-area.skills.edit');

        $controllers->match(
            '/{id}/remove',
            'Application\Controller\MembersArea\SkillsController::removeAction'
        )
        ->bind('members-area.skills.remove');

        return $controllers;
    }
}
