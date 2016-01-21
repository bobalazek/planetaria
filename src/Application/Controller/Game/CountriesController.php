<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

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
}
