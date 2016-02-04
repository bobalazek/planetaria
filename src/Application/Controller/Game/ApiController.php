<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Application\Game\Buildings;

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

    /***** Map *****/
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

    /**
     * @param integer     $id
     * @param Application $app
     * @param Request     $request
     *
     * @return Response
     */
    public function mapBuildAction($id, Application $app, Request $request)
    {
        $planet = $app['orm.em']->find(
            'Application\Entity\PlanetEntity',
            $id
        );

        if (!$planet) {
            return $app->json(array(
                'error' => array(
                    'message' => 'This planet does not exist!',
                ),
            ), 404);
        }

        $x = (int) $request->query->get('x', 0);
        $y = (int) $request->query->get('y', 0);
        $townId = (int) $request->query->get('town_id', 0);
        $building = $request->query->get('building');
        $buildings = Buildings::getAllWithData();

        $tile = $app['orm.em']
            ->getRepository('Application\Entity\TileEntity')
            ->findOneBy(array(
                'planet' => $planet,
                'coordinatesX' => $x,
                'coordinatesY' => $y,
            ))
        ;

        if (!$tile) {
            return $app->json(array(
                'error' => array(
                    'message' => 'This tile does not exist!',
                ),
            ), 404);
        }

        if ($tile->isOccupied()) {
            return $app->json(array(
                'error' => array(
                    'message' => 'This tile is occupied!',
                ),
            ), 403);
        }

        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $townId
        );

        if (!$town) {
            return $app->json(array(
                'error' => array(
                    'message' => 'This town does not exist!',
                ),
            ), 404);
        }

        if (!$app['user']->hasTown($town)) {
            return $app->json(array(
                'error' => array(
                    'message' => 'This is not your town!',
                ),
            ), 403);
        }

        // Update town stuff
        $app['game.towns']->checkForFinishedBuildingUpgrades($town);
        $app['game.towns']->updateTownResources($town);

        if (array_key_exists($building, $buildings)) {
            try {
                $app['game.buildings']->build(
                    $planet,
                    $town,
                    array($x, $y), // Start (bottom left) coordinates
                    $building
                );
            } catch (\Exception $e) {
                return $app->json(array(
                    'error' => array(
                        'message' => $e->getMessage(),
                    ),
                ), 500);
            }
        } else {
            return $app->json(array(
                'error' => array(
                    'message' => 'This building does not exist!',
                ),
            ), 404);
        }

        // Refresh the town, to get the newest data for this town (after the flush)
        $app['orm.em']->refresh($town);

        $buildingChecks = array();
        foreach ($buildings as $buildingKey => $building) {
            $buildingChecks[$buildingKey] = $app['game.buildings']->doPreBuildChecksResponse(
                $planet,
                $town,
                false,
                $buildingKey
            );
        }

        return $app->json(array(
            'message' => 'You have successfully constructed a new building',
            'building_checks' => $buildingChecks,
        ));
    }

    /***** Towns *****/
    /**
     * @param Application $app
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function townsAction(Application $app)
    {
        $towns = array();
        $townsCollection = $app['orm.em']
            ->getRepository('Application\Entity\TownEntity')
            ->findAll()
        ;

        if (!empty($townsCollection)) {
            foreach ($townsCollection as $town) {
                $towns[] = $town->toArray(array(
                    'id', 'name', 'slug', 'description',
                ));
            }
        }

        return $app->json(array(
            'towns' => $towns,
        ));
    }

    /**
     * @param integer     $id
     * @param Application $app
     * @param Request     $request
     *
     * @return Response
     */
    public function townsDetailAction($id, Application $app, Request $request)
    {
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            return $app->json(array(
                'error' => array(
                    'message' => 'This town does not exist!',
                ),
            ), 404);
        }

        // Update town stuff
        $app['game.towns']->checkForFinishedBuildingUpgrades($town);
        $app['game.towns']->updateTownResources($town);

        if ($town->getUser() !== $app['user']) {
            $townFields = array(
                'id', 'name', 'slug', 'description',
            );
        } else {
            $townFields = array(
                'id', 'name', 'slug', 'description',
                'buildings_limit', 'population', 'population_capacity',
                'resources', 'resources_production', 'resources_available', 'resources_capacity',
                'town_buildings.{id,building,building_level}', 'country', 'planet',
            );
        }

        return $app->json($town->toArray($townFields));
    }
}
