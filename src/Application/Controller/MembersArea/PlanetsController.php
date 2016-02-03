<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\PlanetType;
use Application\Entity\PlanetEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class PlanetsController
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
            !$app['security']->isGranted('ROLE_PLANETS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $planetResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('p')
            ->from('Application\Entity\PlanetEntity', 'p')
        ;

        $pagination = $app['paginator']->paginate(
            $planetResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.planets',
                'defaultSortFieldName' => 'p.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    'p.name',
                    'p.slug',
                    'p.description',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/planets/index.html.twig',
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
            !$app['security']->isGranted('ROLE_PLANETS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $form = $app['form.factory']->create(
            new PlanetType(),
            new PlanetEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $planetEntity = $form->getData();

                $app['orm.em']->persist($planetEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.planets.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.planets.edit',
                        array(
                            'id' => $planetEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/members-area/planets/new.html.twig',
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
            !$app['security']->isGranted('ROLE_PLANETS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $planet = $app['orm.em']->find('Application\Entity\PlanetyEntity', $id);

        if (!$planet) {
            $app->abort(404);
        }

        $form = $app['form.factory']->create(
            new PlanetType(),
            $planet
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $planetEntity = $form->getData();

                $app['orm.em']->persist($planetEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.planets.edit.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.planets.edit',
                        array(
                            'id' => $planetEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['planet'] = $planet;

        return new Response(
            $app['twig']->render(
                'contents/members-area/planets/edit.html.twig',
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
            !$app['security']->isGranted('ROLE_PLANETS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $planets = array();
        $ids = $request->query->get('ids', false);
        $idsExploded = explode(',', $ids);
        foreach ($idsExploded as $singleId) {
            $singleEntity = $app['orm.em']->find(
                'Application\Entity\PlanetEntity',
                $singleId
            );

            if ($singleEntity) {
                $planets[] = $singleEntity;
            }
        }

        $planet = $app['orm.em']->find('Application\Entity\PlanetEntity', $id);

        if (
            (
                !$planet &&
                $ids === false
            ) ||
            (
                empty($planets) &&
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
                if (!empty($planets)) {
                    foreach ($planets as $planet) {
                        $app['orm.em']->remove($planet);
                    }
                } else {
                    $app['orm.em']->remove($planet);
                }

                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.planets.remove.successText'
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
                    'members-area.planets'
                )
            );
        }

        $data['planet'] = $planet;
        $data['planets'] = $planets;
        $data['ids'] = $ids;

        return new Response(
            $app['twig']->render(
                'contents/members-area/planets/remove.html.twig',
                $data
            )
        );
    }
}
