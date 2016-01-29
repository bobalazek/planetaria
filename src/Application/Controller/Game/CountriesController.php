<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Entity\CountryEntity;
use Application\Form\Type\My\CountryType;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class CountriesController
{
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Application $app)
    {
        $countries = $app['orm.em']
            ->getRepository('Application\Entity\CountryEntity')
            ->findAll()
        ;

        return new Response(
            $app['twig']->render(
                'contents/game/countries/index.html.twig',
                array(
                    'countries' => $countries,
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
    public function newAction(Request $request, Application $app)
    {
        if (!$app['user']->canCreateNewCountry()) {
            $app->abort(403, 'You can not create a new country!');
        }

        $form = $app['form.factory']->create(
            new CountryType(),
            new CountryEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $countryEntity = $form->getData();

                $countryEntity->setUser($app['user']);
                
                /*** Image ***/
                $countryEntity
                    ->setImageUploadPath($app['baseUrl'].'/assets/uploads/')
                    ->setImageUploadDir(WEB_DIR.'/assets/uploads/')
                    ->imageUpload()
                ;

                $app['orm.em']->persist($countryEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'You have successfully created a new country!'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'game.countries.detail',
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
                'contents/game/countries/new.html.twig',
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
    public function detailAction($id, Application $app)
    {
        $country = $app['orm.em']->find(
            'Application\Entity\CountryEntity',
            $id
        );

        if (!$country) {
            $app->abort(404, 'This country does not exist!');
        }

        return new Response(
            $app['twig']->render(
                'contents/game/countries/detail.html.twig',
                array(
                    'country' => $country,
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
    public function editAction($id, Request $request, Application $app)
    {
        $country = $app['orm.em']->find(
            'Application\Entity\CountryEntity',
            $id
        );

        if (!$country) {
            $app->abort(404, 'This country does not exist!');
        }

        if ($country->getUser() != $app['user']) {
            $app->abort(403, 'This is not your country!');
        }

        $form = $app['form.factory']->create(
            new CountryType(),
            $country
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $countryEntity = $form->getData();
                
                /*** Image ***/
                $countryEntity
                    ->setImageUploadPath($app['baseUrl'].'/assets/uploads/')
                    ->setImageUploadDir(WEB_DIR.'/assets/uploads/')
                    ->imageUpload()
                ;

                $app['orm.em']->persist($countryEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'Your country settings were successfully saved!'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'game.countries.edit',
                        array(
                            'id' => $countryEntity->getId(),
                        )
                    )
                );
            }
        }

        return new Response(
            $app['twig']->render(
                'contents/game/countries/edit.html.twig',
                array(
                    'country' => $country,
                    'form' => $form->createView(),
                )
            )
        );
    }
}
