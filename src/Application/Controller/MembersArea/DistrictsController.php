<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\DistrictType;
use Application\Entity\DistrictEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class DistrictsController
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
            !$app['security']->isGranted('ROLE_DISTRICTS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $districtResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('d')
            ->from('Application\Entity\DistrictEntity', 'd')
        ;

        $pagination = $app['paginator']->paginate(
            $districtResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.districts',
                'defaultSortFieldName' => 'd.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    'd.name',
                    'd.slug',
                    'd.description',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/districts/index.html.twig',
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
            !$app['security']->isGranted('ROLE_DISTRICTS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $form = $app['form.factory']->create(
            new DistrictType(),
            new DistrictEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $districtEntity = $form->getData();

                $app['orm.em']->persist($districtEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.districts.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.districts.edit',
                        array(
                            'id' => $districtEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/members-area/districts/new.html.twig',
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
            !$app['security']->isGranted('ROLE_DISTRICTS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $district = $app['orm.em']->find('Application\Entity\DistrictEntity', $id);

        if (!$district) {
            $app->abort(404);
        }

        $form = $app['form.factory']->create(
            new DistrictType(),
            $district
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $districtEntity = $form->getData();

                $app['orm.em']->persist($districtEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.districts.edit.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.districts.edit',
                        array(
                            'id' => $districtEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['district'] = $district;

        return new Response(
            $app['twig']->render(
                'contents/members-area/districts/edit.html.twig',
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
            !$app['security']->isGranted('ROLE_DISTRICTS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $districts = array();
        $ids = $request->query->get('ids', false);
        $idsExploded = explode(',', $ids);
        foreach ($idsExploded as $singleId) {
            $singleEntity = $app['orm.em']->find(
                'Application\Entity\DistrictEntity',
                $singleId
            );

            if ($singleEntity) {
                $districts[] = $singleEntity;
            }
        }

        $district = $app['orm.em']->find('Application\Entity\DistrictEntity', $id);

        if (
            (
                !$district &&
                $ids === false
            ) ||
            (
                empty($districts) &&
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
                if (!empty($districts)) {
                    foreach ($districts as $district) {
                        $app['orm.em']->remove($district);
                    }
                } else {
                    $app['orm.em']->remove($district);
                }

                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.districts.remove.successText'
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
                    'members-area.districts'
                )
            );
        }

        $data['district'] = $district;
        $data['districts'] = $districts;
        $data['ids'] = $ids;

        return new Response(
            $app['twig']->render(
                'contents/members-area/districts/remove.html.twig',
                $data
            )
        );
    }
}
