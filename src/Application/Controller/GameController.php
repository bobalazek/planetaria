<?php

namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class GameController
{
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/game/index.html.twig'
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function mapAction(Application $app)
    {
        $radius = 16;
        $centerX = 0;
        $centerY = 0;
        
        $coordinatesRangeX = range($centerX-$radius, $centerX+$radius);
        $coordinatesRangeY = range($centerY-$radius, $centerY+$radius);
        
        $tiles = array();
        $tilesArray = $app['orm.em']
            ->getRepository('Application\Entity\TileEntity')
            ->getByCoordinatesRange($coordinatesRangeX, $coordinatesRangeY)
        ;
        
        foreach ($tilesArray as $singleTile) {
            $tiles[$singleTile->getCoordinates()] = $singleTile;
        }
        
        return new Response(
            $app['twig']->render(
                'contents/game/map/index.html.twig',
                array(
                    'tiles' => $tiles,
                    'coordinatesRangeX' => $coordinatesRangeX,
                    'coordinatesRangeY' => $coordinatesRangeY,
                )
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function marketAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/game/market/index.html.twig'
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function statisticsAction(Application $app)
    {
        $users = $app['orm.em']->getRepository('Application\Entity\UserEntity')->findBy(
            array(),
            array('experiencePoints' => 'DESC'),
            10
        );
        $countries = $app['orm.em']->getRepository('Application\Entity\CountryEntity')->findBy(
            array(),
            array(), // TO-Do
            10
        );
        $towns = $app['orm.em']->getRepository('Application\Entity\TownEntity')->findBy(
            array(),
            array(), // TO-Do
            10
        );
        
        return new Response(
            $app['twig']->render(
                'contents/game/statistics/index.html.twig',
                array(
                    'users' => $users,
                    'countries' => $countries,
                    'towns' => $towns,
                )
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function premiumAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/game/premium/index.html.twig'
            )
        );
    }
}
