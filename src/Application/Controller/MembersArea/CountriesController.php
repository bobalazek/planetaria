<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\CountryType;
use Application\Entity\CountryEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class CountriesController
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
            !$app['security']->isGranted('ROLE_COUNTRIES_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $countryResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('c')
            ->from('Application\Entity\CountryEntity', 'c')
        ;

        $pagination = $app['paginator']->paginate(
            $countryResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.countries',
                'defaultSortFieldName' => 'c.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    'c.name',
                    'c.slug',
                    'c.description',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/countries/index.html.twig',
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
            !$app['security']->isGranted('ROLE_COUNTRIES_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $form = $app['form.factory']->create(
            new CountryType(),
            new CountryEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $countryEntity = $form->getData();

                $app['orm.em']->persist($countryEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.countries.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.countries.edit',
                        array(
                            'id' => $countryEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/members-area/countries/new.html.twig',
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
            !$app['security']->isGranted('ROLE_COUNTRIES_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $country = $app['orm.em']->find('Application\Entity\CountryEntity', $id);

        if (!$country) {
            $app->abort(404);
        }

        $form = $app['form.factory']->create(
            new CountryType(),
            $country
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $countryEntity = $form->getData();

                $app['orm.em']->persist($countryEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.countries.edit.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.countries.edit',
                        array(
                            'id' => $countryEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['country'] = $country;

        return new Response(
            $app['twig']->render(
                'contents/members-area/countries/edit.html.twig',
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
            !$app['security']->isGranted('ROLE_COUNTRIES_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $countries = array();
        $ids = $request->query->get('ids', false);
        $idsExploded = explode(',', $ids);
        foreach ($idsExploded as $singleId) {
            $singleEntity = $app['orm.em']->find(
                'Application\Entity\CountryEntity',
                $singleId
            );

            if ($singleEntity) {
                $countries[] = $singleEntity;
            }
        }

        $country = $app['orm.em']->find('Application\Entity\CountryEntity', $id);

        if (
            (
                !$country &&
                $ids === false
            ) ||
            (
                empty($countries) &&
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
                if (!empty($countries)) {
                    foreach ($countries as $country) {
                        $app['orm.em']->remove($country);
                    }
                } else {
                    $app['orm.em']->remove($country);
                }

                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.countries.remove.successText'
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
                    'members-area.countries'
                )
            );
        }

        $data['country'] = $country;
        $data['countries'] = $countries;
        $data['ids'] = $ids;

        return new Response(
            $app['twig']->render(
                'contents/members-area/countries/remove.html.twig',
                $data
            )
        );
    }
}
