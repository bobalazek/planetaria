<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Application\Game\Items;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ItemsController
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
                'contents/members-area/items/index.html.twig',
                array(
                    'items' => Items::getAll(),
                )
            )
        );
    }
}
