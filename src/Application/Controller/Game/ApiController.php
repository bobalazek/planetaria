<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ApiController
{
    /**
     * @param Application $app
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Application $app)
    {
        return $app->json(array(
            'status' => 'success',
            'message' => 'Hello Game API!',
        ));
    }

    /**
     * @param Application $app
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function mapAction(Application $app)
    {
        $planets = array();
        $planetsCollection = $app['orm.em']
            ->getRepository('Application\Entity\PlanetEntity')
            ->findAll()
        ;

        if (!empty($planetsCollection)) {
            foreach ($planetsCollection as $planet) {
                $planets[] = $planet->toArray();
            }
        }

        return $app->json(array(
            'planets' => $planets,
        ));
    }

    /**
     * @param Application $app
     * @param Request     $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function mapDetailAction($id, Application $app, Request $request)
    {
        $planet = $app['orm.em']->find(
            'Application\Entity\PlanetEntity',
            $id
        );

        if (!$planet) {
            return $app->json(array(
                'error' => $app['translator']->trans('This planet does not exists!'),
            ), 404);
        }

        $radius = (int) $request->query->get('radius', 16);
        $centerX = (int) $request->query->get('x', 0);
        $centerY = (int) $request->query->get('y', 0);
        $coordinatesRangeX = range($centerX - $radius, $centerX + $radius);
        $coordinatesRangeY = range($centerY - $radius, $centerY + $radius);

        $tiles = array();
        $tilesArray = $app['orm.em']
            ->getRepository('Application\Entity\TileEntity')
            ->getByCoordinatesRange($coordinatesRangeX, $coordinatesRangeY, $planet)
        ;

        foreach ($tilesArray as $singleTile) {
            $tiles[$singleTile->getCoordinates()] = $singleTile->toArray(array(
                'id', 'terrain_type', 'status', 'background_image',
                'coordinates', 'coordinates_x', 'coordinates_y',
                'buildable', 'currently_buildable',
                'town_building.{id,building,building_object,level,status,health_points,health_points_total,health_points_percentage,at_maximum_level,operational,upgradable,upgrading,constructing,image,time_constructed,time_next_level_upgrade_started,time_next_level_upgrade_ends}',
                'building_section',
            ));
        }

        return $app->json(array(
            'planet' => $planet->toArray(),
            'tiles' => $tiles,
            'coordinates_range_x' => $coordinatesRangeX,
            'coordinates_range_y' => $coordinatesRangeY,
            'center_x' => $centerX,
            'center_y' => $centerY,
            'radius' => $radius,
        ));
    }
}
