<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Application\Game\Skills;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class SkillsController
{
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/members-area/skills/index.html.twig',
                array(
                    'skills' => Skills::getAll(),
                )
            )
        );
    }
}
