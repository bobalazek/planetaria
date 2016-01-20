<?php

namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Application\Game\Buildings;
use Application\Game\BuildingTypes;

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
        $planets = $app['orm.em']
            ->getRepository('Application\Entity\PlanetEntity')
            ->findAll()
        ;

        if (count($planets) === 1) {
            return $app->redirect($app['url_generator']->generate(
                'game.map.detail',
                array(
                    'id' => $planets[0]->getId(),
                )
            ));
        }

        return new Response(
            $app['twig']->render(
                'contents/game/map/index.html.twig',
                array(
                    'planets' => $planets,
                )
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function mapDetailAction($id, Application $app, Request $request)
    {
        $planet = $app['orm.em']->find(
            'Application\Entity\PlanetEntity',
            $id
        );

        if (!$planet) {
            $app->abort(404);
        }

        $radius = 16;
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
            $tiles[$singleTile->getCoordinates()] = $singleTile;
        }

        return new Response(
            $app['twig']->render(
                'contents/game/map/detail/index.html.twig',
                array(
                    'planet' => $planet,
                    'tiles' => $tiles,
                    'coordinatesRangeX' => $coordinatesRangeX,
                    'coordinatesRangeY' => $coordinatesRangeY,
                    'centerX' => $centerX,
                    'centerY' => $centerY,
                )
            )
        );
    }

    /**
     * @param Application $app
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
            $app->abort(404);
        }

        $x = (int) $request->query->get('x', 0);
        $y = (int) $request->query->get('y', 0);

        $tile = $app['orm.em']
            ->getRepository('Application\Entity\TileEntity')
            ->findOneBy(array(
                'planet' => $planet,
                'coordinatesX' => $x,
                'coordinatesY' => $y,
            ))
        ;

        if (!$tile) {
            $app->abort(404);
        }

        if (!$tile->isBuildableCurrently()) {
            $app->abort(404);
        }

        $townId = (int) $request->query->get('town_id', 0);
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $townId
        );

        if ($town) {
            // Update town stuff
            $app['game.towns']->checkForFinishedBuildingUpgrades($town);
            $app['game.towns']->updateTownResources($town);
        }

        // Get all available buildings and building types
        $buildings = Buildings::getAllWithData();
        $buildingTypes = BuildingTypes::getAll();

        $building = $request->query->get('building');
        if ($building && $town) {
            if (array_key_exists($building, $buildings)) {
                try {
                    $townBuilding = $app['game.buildings']->build(
                        $planet,
                        $town,
                        array($x, $y), // Start (bottom left) coordinates
                        $building
                    );

                    $app['flashbag']->add(
                        'success',
                        $app['translator']->trans(
                            'The building ":building:" has started building!',
                            array(
                                ':building:' => Buildings::getAll($building),
                            )
                        )
                    );

                    return $app->redirect(
                        $app['url_generator']->generate(
                            'game.towns.buildings.detail',
                            array(
                                'id' => $townBuilding->getTown()->getId(),
                                'buildingId' => $townBuilding->getId(),
                            )
                        )
                    );
                } catch (\Exception $e) {
                    $app['flashbag']->add(
                        'danger',
                        $app['translator']->trans(
                            $e->getMessage()
                        )
                    );

                    return $app->redirect(
                        $app['url_generator']->generate(
                            'game.map.build',
                            array(
                                'id' => $planet->getId(),
                                'x' => $x,
                                'y' => $y,
                                'town_id' => $townId,
                            )
                        )
                    );
                }
            } else {
                $app['flashbag']->add(
                    'danger',
                    $app['translator']->trans(
                        'The building ":building:" does not exists!',
                        array(
                            ':building:' => $building,
                        )
                    )
                );
            }
        }

        return new Response(
            $app['twig']->render(
                'contents/game/map/build.html.twig',
                array(
                    'buildings' => $buildings,
                    'buildingTypes' => $buildingTypes,
                    'planet' => $planet,
                    'tile' => $tile,
                    'town' => $town,
                    'x' => $x,
                    'y' => $y,
                )
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
