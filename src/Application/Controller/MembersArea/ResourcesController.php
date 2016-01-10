<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\ResourceType;
use Application\Entity\ResourceEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ResourcesController
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
            !$app['security']->isGranted('ROLE_RESOURCES_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $resourceResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('r')
            ->from('Application\Entity\ResourceEntity', 'r')
        ;

        $pagination = $app['paginator']->paginate(
            $resourceResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.resources',
                'defaultSortFieldName' => 'r.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    'r.name',
                    'r.slug',
                    'r.description',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/resources/index.html.twig',
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
            !$app['security']->isGranted('ROLE_RESOURCES_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $form = $app['form.factory']->create(
            new ResourceType(),
            new ResourceEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $resourceEntity = $form->getData();

                $app['orm.em']->persist($resourceEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.resources.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.resources.edit',
                        array(
                            'id' => $resourceEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/members-area/resources/new.html.twig',
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
            !$app['security']->isGranted('ROLE_RESOURCES_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $resource = $app['orm.em']->find('Application\Entity\ResourceEntity', $id);

        if (!$resource) {
            $app->abort(404);
        }

        $form = $app['form.factory']->create(
            new ResourceType(),
            $resource
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $resourceEntity = $form->getData();

                $app['orm.em']->persist($resourceEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.resources.edit.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.resources.edit',
                        array(
                            'id' => $resourceEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['resource'] = $resource;

        return new Response(
            $app['twig']->render(
                'contents/members-area/resources/edit.html.twig',
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
            !$app['security']->isGranted('ROLE_RESOURCES_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $resources = array();
        $ids = $request->query->get('ids', false);
        $idsExploded = explode(',', $ids);
        foreach ($idsExploded as $singleId) {
            $singleEntity = $app['orm.em']->find(
                'Application\Entity\ResourceEntity',
                $singleId
            );

            if ($singleEntity) {
                $resources[] = $singleEntity;
            }
        }

        $resource = $app['orm.em']->find('Application\Entity\ResourceEntity', $id);

        if (
            (
                !$resource &&
                $ids === false
            ) ||
            (
                empty($resources) &&
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
                if (!empty($resources)) {
                    foreach ($resources as $resource) {
                        $app['orm.em']->remove($resource);
                    }
                } else {
                    $app['orm.em']->remove($resource);
                }

                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.resources.remove.successText'
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
                    'members-area.resources'
                )
            );
        }

        $data['resource'] = $resource;
        $data['resources'] = $resources;
        $data['ids'] = $ids;

        return new Response(
            $app['twig']->render(
                'contents/members-area/resources/remove.html.twig',
                $data
            )
        );
    }
}
