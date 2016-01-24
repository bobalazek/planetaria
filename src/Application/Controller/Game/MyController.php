<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\My\SettingsType;
use Application\Form\Type\My\PasswordType;
use Application\Entity\UserMessageEntity;
use Application\Form\Type\My\UserMessageType;

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
        return new Response(
            $app['twig']->render(
                'contents/game/my/profile.html.twig'
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

    /********** Messages **********/
    /**
     * @param Application $app
     * @param Request $request
     *
     * @return Response
     */
    public function messagesAction(Application $app, Request $request)
    {
        $unseenUserMessages = $app['orm.em']
            ->getRepository(
                'Application\Entity\UserMessageEntity'
            )
            ->findBy(
                array(
                    'user' => $app['user'],
                    'timeSeen' => null,
                )
            )
        ;
        
        if ($unseenUserMessages) {
            foreach ($unseenUserMessages as $unseenUserMessage) {
                $unseenUserMessage->setTimeSeen(new \DateTime());

                $app['orm.em']->persist($unseenUserMessage);
            }

            $app['orm.em']->flush();
        }
        
        $limitPerPage = $request->query->get('limit_per_page', 10);
        $currentPage = $request->query->get('page');
        $folder = $request->query->get('folder', 'inbox');
        $folder = $folder == 'inbox' || $folder == 'sent'
            ? $folder
            : 'inbox'
        ;
        
        $userMessageResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('um, uf, u')
            ->from('Application\Entity\UserMessageEntity', 'um')
            ->leftJoin('um.user', 'u')
            ->leftJoin('um.userFrom', 'uf')
        ;

        if ($folder == 'inbox') {
            $userMessageResults
                ->where('um.user = ?1')
                ->setParameter(1, $app['user'])
            ;
        } elseif ($folder == 'sent') {
            $userMessageResults
                ->where('um.userFrom = ?1')
                ->setParameter(1, $app['user'])
            ;
        }

        $pagination = $app['paginator']->paginate(
            $userMessageResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'game.my.messages',
                'defaultSortFieldName' => 'um.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    'um.title',
                    'um.content',
                    'uf.username',
                    'uf.email',
                ),
            )
        );
        
        $data['folder'] = $folder;
        $data['pagination'] = $pagination;
        
        return new Response(
            $app['twig']->render(
                'contents/game/my/messages.html.twig',
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
    public function messagesNewAction(Request $request, Application $app)
    {
        $data = array();

        $form = $app['form.factory']->create(
            new UserMessageType(),
            new UserMessageEntity(),
            array(
                'user' => $app['user'],
            )
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $userMessageEntity = $form->getData();

                $userMessageEntity
                    ->setUserFrom(
                        $app['user']
                    )
                ;

                $app['orm.em']->persist($userMessageEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'game.my.messages.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'game.my.messages',
                        array(
                            'folder' => 'sent',
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/game/my/messages/new.html.twig',
                $data
            )
        );
    }
    
    /**
     * @param integer     $id
     * @param Application $app
     *
     * @return Response
     */
    public function messagesDetailAction($id, Application $app)
    {
        $userMessage = $app['orm.em']->find(
            'Application\Entity\UserMessageEntity',
            $id
        );

        if (!$userMessage) {
            $app->abort(404, 'This message does not exist!');
        }

        return new Response(
            $app['twig']->render(
                'contents/game/my/messages/detail.html.twig',
                array(
                    'userMessage' => $userMessage,
                )
            )
        );
    }
    
    /**
     * @param $id
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function messagesReplyAction($id, Request $request, Application $app)
    {
        $data = array();

        $originalUserMessage = $app['orm.em']->find(
            'Application\Entity\UserMessageEntity',
            $id
        );

        if (!$originalUserMessage) {
            $app->abort(404);
        }

        if (!$originalUserMessage->getUser() == $app['user']) {
            $app->abort(403);
        }

        $userMessage = new UserMessageEntity();
        $userMessage
            ->setSubject(
                $app['translator']->trans('RE').
                ': '.
                $originalUserMessage->getSubject()
            )
            ->setUser(
                $originalUserMessage->getUserFrom()
            )
            ->setUserFrom(
                $originalUserMessage->getUser()
            )
            ->setParentUserMessage(
                $originalUserMessage
            )
        ;

        $form = $app['form.factory']->create(
            new UserMessageType(),
            $userMessage
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $userMessageEntity = $form->getData();

                $app['orm.em']->persist($userMessageEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'game.my.messages.reply.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'game.my.messages',
                        array(
                            'folder' => 'sent',
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['userMessage'] = $userMessage;
        $data['originalUserMessage'] = $originalUserMessage;

        return new Response(
            $app['twig']->render(
                'contents/game/my/messages/reply.html.twig',
                $data
            )
        );
    }
    
    /********** Notifications **********/
    /**
     * @param Application $app
     * @param Request $request
     *
     * @return Response
     */
    public function notificationsAction(Application $app, Request $request)
    {
        $data = array();

        $limitPerPage = $request->query->get('limit_per_page', 10);
        $currentPage = $request->query->get('page');

        $unseenUserNotifications = $app['orm.em']
            ->getRepository(
                'Application\Entity\UserNotificationEntity'
            )
            ->findBy(
                array(
                    'user' => $app['user'],
                    'timeSeen' => null,
                )
            )
        ;
        
        if ($unseenUserNotifications) {
            foreach ($unseenUserNotifications as $unseenUserNotification) {
                $unseenUserNotification->setTimeSeen(new \DateTime());

                $app['orm.em']->persist($unseenUserNotification);
            }

            $app['orm.em']->flush();
        }
        
        $userNotificationResults = $app['orm.em']
            ->createQuery(
                "SELECT un
                    FROM Application\Entity\UserNotificationEntity un
                    WHERE un.user = :user"
            )
            ->setParameter('user', $app['user'])
        ;

        $pagination = $app['paginator']->paginate(
            $userNotificationResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'game.my.notifications',
                'defaultSortFieldName' => 'un.timeCreated',
                'defaultSortDirection' => 'desc',
            )
        );

        $data['pagination'] = $pagination;
        
        return new Response(
            $app['twig']->render(
                'contents/game/my/notifications.html.twig',
                $data
            )
        );
    }
    
    /**
     * @param $id
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function notificationsAcknowledgeAction($id, Request $request, Application $app)
    {
        $userNotification = $app['orm.em']->find(
            'Application\Entity\UserNotificationEntity',
            $id
        );

        if (!$userNotification) {
            $app->abort(404);
        }

        if (
            $userNotification->getUser() == $app['user'] &&
            $userNotification->getTimeAcknowledged() == null
        ) {
            $userNotification
                ->setTimeAcknowledged(new \DateTime())
            ;

            $app['orm.em']->persist($userNotification);
            $app['orm.em']->flush();

            $app['flashbag']->add(
                'success',
                $app['translator']->trans(
                    'game.my.notifications.acknowledge.successText'
                )
            );
        }

        return $app->redirect(
            $app['url_generator']->generate(
                'game.my.notifications'
            )
        );
    }
}
