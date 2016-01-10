<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\UnitType;
use Application\Entity\UnitEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UnitsController
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
            !$app['security']->isGranted('ROLE_UNITS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $unitResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('u')
            ->from('Application\Entity\UnitEntity', 'u')
        ;

        $pagination = $app['paginator']->paginate(
            $unitResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.units',
                'defaultSortFieldName' => 'u.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    'u.name',
                    'u.slug',
                    'u.description',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/units/index.html.twig',
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
            !$app['security']->isGranted('ROLE_UNITS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $form = $app['form.factory']->create(
            new UnitType(),
            new UnitEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $unitEntity = $form->getData();

                $app['orm.em']->persist($unitEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.units.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.units.edit',
                        array(
                            'id' => $unitEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/members-area/units/new.html.twig',
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
            !$app['security']->isGranted('ROLE_UNITS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $unit = $app['orm.em']->find('Application\Entity\UnitEntity', $id);

        if (!$unit) {
            $app->abort(404);
        }

        $form = $app['form.factory']->create(
            new UnitType(),
            $unit
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $unitEntity = $form->getData();

                $app['orm.em']->persist($unitEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.units.edit.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.units.edit',
                        array(
                            'id' => $unitEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['unit'] = $unit;

        return new Response(
            $app['twig']->render(
                'contents/members-area/units/edit.html.twig',
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
            !$app['security']->isGranted('ROLE_UNITS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $units = array();
        $ids = $request->query->get('ids', false);
        $idsExploded = explode(',', $ids);
        foreach ($idsExploded as $singleId) {
            $singleEntity = $app['orm.em']->find(
                'Application\Entity\UnitEntity',
                $singleId
            );

            if ($singleEntity) {
                $units[] = $singleEntity;
            }
        }

        $unit = $app['orm.em']->find('Application\Entity\UnitEntity', $id);

        if (
            (
                !$unit &&
                $ids === false
            ) ||
            (
                empty($units) &&
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
                if (!empty($units)) {
                    foreach ($units as $unit) {
                        $app['orm.em']->remove($unit);
                    }
                } else {
                    $app['orm.em']->remove($unit);
                }

                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.units.remove.successText'
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
                    'members-area.units'
                )
            );
        }

        $data['unit'] = $unit;
        $data['units'] = $units;
        $data['ids'] = $ids;

        return new Response(
            $app['twig']->render(
                'contents/members-area/units/remove.html.twig',
                $data
            )
        );
    }
}
