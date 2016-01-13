<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UsersController
{
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Application $app)
    {
        $users = $app['orm.em']
            ->getRepository('Application\Entity\UserEntity')
            ->findAll()
        ;

        return new Response(
            $app['twig']->render(
                'contents/game/users/index.html.twig',
                array(
                    'users' => $users,
                )
            )
        );
    }

    /**
     * @param integer     $id
     * @param Application $app
     *
     * @return Response
     */
    public function detailAction($id, Application $app)
    {
        $user = $app['orm.em']->find(
            'Application\Entity\UserEntity',
            $id
        );

        if (!$user) {
            $app->abort(404);
        }

        return new Response(
            $app['twig']->render(
                'contents/game/users/detail.html.twig',
                array(
                    'user' => $user,
                )
            )
        );
    }
}
