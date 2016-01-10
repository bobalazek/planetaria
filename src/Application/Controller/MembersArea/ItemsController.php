<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\ItemType;
use Application\Entity\ItemEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ItemsController
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
            !$app['security']->isGranted('ROLE_ITEMS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $itemResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('i')
            ->from('Application\Entity\ItemEntity', 'i')
        ;

        $pagination = $app['paginator']->paginate(
            $itemResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.items',
                'defaultSortFieldName' => 'i.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    'i.name',
                    'i.slug',
                    'i.description',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/items/index.html.twig',
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
            !$app['security']->isGranted('ROLE_ITEMS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $form = $app['form.factory']->create(
            new ItemType(),
            new ItemEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $itemEntity = $form->getData();

                $app['orm.em']->persist($itemEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.items.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.items.edit',
                        array(
                            'id' => $itemEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/members-area/items/new.html.twig',
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
            !$app['security']->isGranted('ROLE_ITEMS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $item = $app['orm.em']->find('Application\Entity\ItemEntity', $id);

        if (!$item) {
            $app->abort(404);
        }

        $form = $app['form.factory']->create(
            new ItemType(),
            $item
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $itemEntity = $form->getData();

                $app['orm.em']->persist($itemEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.items.edit.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.items.edit',
                        array(
                            'id' => $itemEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['item'] = $item;

        return new Response(
            $app['twig']->render(
                'contents/members-area/items/edit.html.twig',
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
            !$app['security']->isGranted('ROLE_ITEMS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $items = array();
        $ids = $request->query->get('ids', false);
        $idsExploded = explode(',', $ids);
        foreach ($idsExploded as $singleId) {
            $singleEntity = $app['orm.em']->find(
                'Application\Entity\ItemEntity',
                $singleId
            );

            if ($singleEntity) {
                $items[] = $singleEntity;
            }
        }

        $item = $app['orm.em']->find('Application\Entity\ItemEntity', $id);

        if (
            (
                !$item &&
                $ids === false
            ) ||
            (
                empty($items) &&
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
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $app['orm.em']->remove($item);
                    }
                } else {
                    $app['orm.em']->remove($item);
                }

                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.items.remove.successText'
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
                    'members-area.items'
                )
            );
        }

        $data['item'] = $item;
        $data['items'] = $items;
        $data['ids'] = $ids;

        return new Response(
            $app['twig']->render(
                'contents/members-area/items/remove.html.twig',
                $data
            )
        );
    }
}
