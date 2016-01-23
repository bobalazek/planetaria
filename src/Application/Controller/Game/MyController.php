<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\User\SettingsType;
use Application\Form\Type\User\Settings\PasswordType;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class MyController
{
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Application $app)
    {
        return $app->redirect(
            $app['url_generator']->generate('game.my.profile')
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function profileAction(Application $app)
    {
        return $app->redirect(
            $app['url_generator']->generate(
                'game.users.detail',
                array(
                    'id' => $app['user']->getId(),
                )
            )
        );
    }

    /**
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function settingsAction(Request $request, Application $app)
    {
        $data = array();

        $form = $app['form.factory']->create(
            new SettingsType(),
            $app['user']
        );

        // IMPORTANT Security fix!
        $currentUserUsername = $app['user']->getUsername();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            // IMPORTANT Security fix!
            /*
             * Some weird bug here allows to impersonate to another user
             *   by just changing to his (like some admins) username
             *   (after failed "username already used" message)
             *   when the validation kicks in, and one refresh later,
             *   you're logged in as that user.
             */
            $app['user']->setUsername($currentUserUsername);

            if ($form->isValid()) {
                $userEntity = $form->getData();

                /*** Image ***/
                $userEntity
                    ->getProfile()
                    ->setImageUploadPath($app['baseUrl'].'/assets/uploads/')
                    ->setImageUploadDir(WEB_DIR.'/assets/uploads/')
                    ->imageUpload()
                ;

                $app['orm.em']->persist($userEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'Your settings were successfully updated!'
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/game/my/settings.html.twig',
                $data
            )
        );
    }

    /**
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function passwordAction(Request $request, Application $app)
    {
        $data = array();

        $form = $app['form.factory']->create(
            new PasswordType(),
            $app['user']
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $userEntity = $form->getData();

                if ($userEntity->getPlainPassword()) {
                    $userEntity->setPlainPassword(
                        $userEntity->getPlainPassword(),
                        $app['security.encoder_factory']
                    );

                    $app['orm.em']->persist($userEntity);
                    $app['orm.em']->flush();

                    $app['flashbag']->add(
                        'success',
                        $app['translator']->trans(
                            'Your password was successfully changed! '
                        )
                    );
                }
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/game/my/password.html.twig',
                $data
            )
        );
    }
    
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function messagesAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/game/my/messages.html.twig'
            )
        );
    }
}
