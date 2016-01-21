<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Application\Game\Buildings;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownsController
{
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Application $app)
    {
        $towns = $app['orm.em']
            ->getRepository('Application\Entity\TownEntity')
            ->findAll()
        ;

        return new Response(
            $app['twig']->render(
                'contents/game/towns/index.html.twig',
                array(
                    'towns' => $towns,
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
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            $app->abort(404);
        }

        // Update town stuff
        $app['game.towns']->checkForFinishedBuildingUpgrades($town);
        $app['game.towns']->updateTownResources($town);

        return new Response(
            $app['twig']->render(
                'contents/game/towns/detail.html.twig',
                array(
                    'town' => $town,
                )
            )
        );
    }

    /**
     * @param integer     $id
     * @param integer     $buildingId
     * @param Application $app
     *
     * @return Response
     */
    public function buildingsDetailAction($id, $buildingId, Application $app)
    {
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            $app->abort(404);
        }

        $townBuilding = $app['orm.em']->find(
            'Application\Entity\TownBuildingEntity',
            $buildingId
        );

        if (!$townBuilding) {
            $app->abort(404);
        }

        // Update town stuff
        $app['game.towns']->checkForFinishedBuildingUpgrades($town);
        $app['game.towns']->updateTownResources($town);

        return new Response(
            $app['twig']->render(
                'contents/game/towns/buildings/detail.html.twig',
                array(
                    'town' => $town,
                    'townBuilding' => $townBuilding,
                )
            )
        );
    }

    /**
     * @param integer     $id
     * @param integer     $buildingId
     * @param Application $app
     *
     * @return Response
     */
    public function buildingsUpgradeAction($id, $buildingId, Application $app)
    {
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            $app->abort(404);
        }

        $townBuilding = $app['orm.em']->find(
            'Application\Entity\TownBuildingEntity',
            $buildingId
        );

        if (!$townBuilding) {
            $app->abort(404);
        }

        if (!$townBuilding->isUpgradable()) {
            $app['flashbag']->add(
                'danger',
                $app['translator']->trans(
                    'This building is not upgradable!'
                )
            );
        } else {
            // Update town stuff
            $app['game.towns']->checkForFinishedBuildingUpgrades($town);
            $app['game.towns']->updateTownResources($town);

            try {
                $app['game.buildings']
                    ->upgrade($townBuilding)
                ;

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'The upgrade for this building has started!'
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
        }

        return $app->redirect(
            $app['url_generator']->generate(
                'game.towns.buildings.detail',
                array(
                    'id' => $town->getId(),
                    'buildingId' => $townBuilding->getId(),
                )
            )
        );
    }

    /**
     * @param integer     $id
     * @param integer     $buildingId
     * @param Application $app
     *
     * @return Response
     */
    public function buildingsRemoveAction($id, $buildingId, Application $app)
    {
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            $app->abort(404);
        }

        $townBuilding = $app['orm.em']->find(
            'Application\Entity\TownBuildingEntity',
            $buildingId
        );

        if (!$townBuilding) {
            $app->abort(404);
        }

        $townBuildingTiles = $townBuilding->getTiles();

        if (!empty($townBuildingTiles)) {
            foreach ($townBuildingTiles as $townBuildingTile) {
                $townBuildingTile
                    ->setTownBuilding(null)
                    ->setBuildingSection(null)
                ;
                $app['orm.em']->persist($townBuildingTile);
            }
        }

        $app['orm.em']->remove($townBuilding);

        try {
            if (!$townBuilding->isRemovable()) {
                throw new \Exception('This building is not removable!');
            }

            $app['orm.em']->flush();

            $app['flashbag']->add(
                'success',
                $app['translator']->trans(
                    'The building was successfully removed!'
                )
            );
        } catch (\Exception $e) {
            $app['flashbag']->add(
                'danger',
                $e->getMessage()
            );

            return $app->redirect(
                $app['url_generator']->generate(
                    'game.towns.buildings.detail',
                    array(
                        'id' => $town->getId(),
                        'buildingId' => $townBuilding->getId(),
                    )
                )
            );
        }

        return $app->redirect(
            $app['url_generator']->generate(
                'game.map.detail',
                array(
                    'id' => $townBuilding->getTown()->getCountry()->getId(),
                    'x' => $townBuilding->getCoordinatesX(),
                    'y' => $townBuilding->getCoordinatesX(),
                )
            )
        );
    }
}
