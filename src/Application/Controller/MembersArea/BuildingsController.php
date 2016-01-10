<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\BuildingType;
use Application\Entity\BuildingEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class BuildingsController
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
            !$app['security']->isGranted('ROLE_BUILDINGS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $buildingResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('b')
            ->from('Application\Entity\BuildingEntity', 'b')
        ;

        $pagination = $app['paginator']->paginate(
            $buildingResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.buildings',
                'defaultSortFieldName' => 'b.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    'b.name',
                    'b.slug',
                    'b.description',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/buildings/index.html.twig',
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
            !$app['security']->isGranted('ROLE_BUILDINGS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $form = $app['form.factory']->create(
            new BuildingType(),
            new BuildingEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $buildingEntity = $form->getData();

                $app['orm.em']->persist($buildingEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.buildings.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.buildings.edit',
                        array(
                            'id' => $buildingEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/members-area/buildings/new.html.twig',
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
            !$app['security']->isGranted('ROLE_BUILDINGS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $building = $app['orm.em']->find('Application\Entity\BuildingEntity', $id);

        if (!$building) {
            $app->abort(404);
        }

        $form = $app['form.factory']->create(
            new BuildingType(),
            $building
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $buildingEntity = $form->getData();

                $app['orm.em']->persist($buildingEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.buildings.edit.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.buildings.edit',
                        array(
                            'id' => $buildingEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['building'] = $building;

        return new Response(
            $app['twig']->render(
                'contents/members-area/buildings/edit.html.twig',
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
            !$app['security']->isGranted('ROLE_BUILDINGS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $buildings = array();
        $ids = $request->query->get('ids', false);
        $idsExploded = explode(',', $ids);
        foreach ($idsExploded as $singleId) {
            $singleEntity = $app['orm.em']->find(
                'Application\Entity\BuildingEntity',
                $singleId
            );

            if ($singleEntity) {
                $buildings[] = $singleEntity;
            }
        }

        $building = $app['orm.em']->find('Application\Entity\BuildingEntity', $id);

        if (
            (
                !$building &&
                $ids === false
            ) ||
            (
                empty($buildings) &&
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
                if (!empty($buildings)) {
                    foreach ($buildings as $building) {
                        $app['orm.em']->remove($building);
                    }
                } else {
                    $app['orm.em']->remove($building);
                }

                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.buildings.remove.successText'
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
                    'members-area.buildings'
                )
            );
        }

        $data['building'] = $building;
        $data['buildings'] = $buildings;
        $data['ids'] = $ids;

        return new Response(
            $app['twig']->render(
                'contents/members-area/buildings/remove.html.twig',
                $data
            )
        );
    }
}
