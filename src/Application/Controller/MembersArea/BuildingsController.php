<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Application\Game\Buildings;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class BuildingsController
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
                'contents/members-area/buildings/index.html.twig',
                array(
                    'buildings' => Buildings::getAll(),
                )
            )
        );
    }
}
