<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\TownType;
use Application\Entity\TownEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownsController
{
    /**
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Request $request, Application $app)
    {
        $data = array();

        if (
            !$app['security']->isGranted('ROLE_TOWNS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $townResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('t')
            ->from('Application\Entity\TownEntity', 't')
        ;

        $pagination = $app['paginator']->paginate(
            $townResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.towns',
                'defaultSortFieldName' => 't.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    't.name',
                    't.slug',
                    't.description',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/towns/index.html.twig',
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
    public function newAction(Request $request, Application $app)
    {
        $data = array();

        if (
            !$app['security']->isGranted('ROLE_TOWNS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $form = $app['form.factory']->create(
            new TownType(),
            new TownEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $townEntity = $form->getData();

                $app['orm.em']->persist($townEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.towns.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.towns.edit',
                        array(
                            'id' => $townEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/members-area/towns/new.html.twig',
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
    public function editAction($id, Request $request, Application $app)
    {
        $data = array();

        if (
            !$app['security']->isGranted('ROLE_TOWNS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $town = $app['orm.em']->find('Application\Entity\TownEntity', $id);

        if (!$town) {
            $app->abort(404);
        }

        $form = $app['form.factory']->create(
            new TownType(),
            $town
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $townEntity = $form->getData();

                $app['orm.em']->persist($townEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.towns.edit.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.towns.edit',
                        array(
                            'id' => $townEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['town'] = $town;

        return new Response(
            $app['twig']->render(
                'contents/members-area/towns/edit.html.twig',
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
    public function removeAction($id, Request $request, Application $app)
    {
        $data = array();

        if (
            !$app['security']->isGranted('ROLE_TOWNS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $towns = array();
        $ids = $request->query->get('ids', false);
        $idsExploded = explode(',', $ids);
        foreach ($idsExploded as $singleId) {
            $singleEntity = $app['orm.em']->find(
                'Application\Entity\TownEntity',
                $singleId
            );

            if ($singleEntity) {
                $towns[] = $singleEntity;
            }
        }

        $town = $app['orm.em']->find('Application\Entity\TownEntity', $id);

        if (
            (
                !$town &&
                $ids === false
            ) ||
            (
                empty($towns) &&
                $ids !== false
            )
        ) {
            $app->abort(404);
        }

        $confirmAction = $app['request']->query->has('action') &&
            $app['request']->query->get('action') == 'confirm'
        ;

        if ($confirmAction) {
            try {
                if (!empty($towns)) {
                    foreach ($towns as $town) {
                        $app['orm.em']->remove($town);
                    }
                } else {
                    $app['orm.em']->remove($town);
                }

                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.towns.remove.successText'
                    )
                );
            } catch (\Exception $e) {
                $app['flashbag']->add(
                    'danger',
                    $app['translator']->trans(
                        $e->getMessage()
                    )
                );
            }

            return $app->redirect(
                $app['url_generator']->generate(
                    'members-area.towns'
                )
            );
        }

        $data['town'] = $town;
        $data['towns'] = $towns;
        $data['ids'] = $ids;

        return new Response(
            $app['twig']->render(
                'contents/members-area/towns/remove.html.twig',
                $data
            )
        );
    }
}
