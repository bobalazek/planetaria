<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Application\Game\Resources;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ResourcesController
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
                'contents/members-area/resources/index.html.twig',
                array(
                    'resources' => Resources::getAll(),
                )
            )
        );
    }
}
