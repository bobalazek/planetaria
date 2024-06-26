<?php

namespace Application\Controller\Game;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Entity\TownEntity;
use Application\Form\Type\My\TownType;
use Application\Game\Buildings;
use Application\Game\Exception\InsufficientResourcesException;

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
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function newAction(Request $request, Application $app)
    {
        if (!$app['user']->canCreateNewTown()) {
            $app->abort(403, 'You can not create a new town!');
        }

        if (count($app['user']->getCountries()) < 1) {
            $app->abort(403, 'You need at least one country to which you can assign this town!');
        }

        $form = $app['form.factory']->create(
            new TownType(),
            new TownEntity(),
            array(
                'user' => $app['user'],
            )
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $planet = $app['orm.em']->find('Application\Entity\PlanetEntity', 1);
                $townEntity = $form->getData();

                /*** Image ***/
                $townEntity
                    ->setImageUploadPath($app['baseUrl'].'/assets/uploads/')
                    ->setImageUploadDir(WEB_DIR.'/assets/uploads/')
                    ->imageUpload()
                ;

                $townEntity
                    ->setUser($app['user'])
                    ->setPlanet($planet)
                ;

                $app['orm.em']->persist($townEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'You have successfully created a new town!'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'game.towns.detail',
                        array(
                            'id' => $townEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/game/towns/new.html.twig',
                $data
            )
        );
    }

    /**
     * @param integer     $id
     * @param Application $app
     * @param Request     $request
     *
     * @return Response
     */
    public function detailAction($id, Application $app, Request $request)
    {
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            $app->abort(404, 'This town does not exist!');
        }

        // Update town stuff
        $app['game.towns']->checkForFinishedBuildingUpgrades($town);
        $app['game.towns']->updateTownResources($town);

        return new Response(
            $app['twig']->render(
                'contents/game/towns/detail.html.twig',
                array(
                    'town' => $town,
                    'townId' => $id,
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
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            $app->abort(404, 'This town does not exist!');
        }

        if ($town->getUser() != $app['user']) {
            $app->abort(403, 'This is not your town!');
        }

        $form = $app['form.factory']->create(
            new TownType(),
            $town
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $townEntity = $form->getData();

                /*** Image ***/
                $townEntity
                    ->setImageUploadPath($app['baseUrl'].'/assets/uploads/')
                    ->setImageUploadDir(WEB_DIR.'/assets/uploads/')
                    ->imageUpload()
                ;

                $app['orm.em']->persist($townEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'Your town settings were successfully saved!'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'game.towns.edit',
                        array(
                            'id' => $townEntity->getId(),
                        )
                    )
                );
            }
        }

        return new Response(
            $app['twig']->render(
                'contents/game/towns/edit.html.twig',
                array(
                    'town' => $town,
                    'form' => $form->createView(),
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
            $app->abort(404, 'This town does not exist!');
        }

        $townBuilding = $app['orm.em']->find(
            'Application\Entity\TownBuildingEntity',
            $buildingId
        );

        if (!$townBuilding) {
            $app->abort(404, 'This town building does not exist!');
        }

        if (!$app['user']->hasTownBuilding($townBuilding)) {
            $app->abort(403, 'This is not your town building!');
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
     * @param Request     $request
     *
     * @return Response
     */
    public function buildingsUpgradeAction($id, $buildingId, Application $app, Request $request)
    {
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            $app->abort(404, 'This town does not exist!');
        }

        $townBuilding = $app['orm.em']->find(
            'Application\Entity\TownBuildingEntity',
            $buildingId
        );

        if (!$townBuilding) {
            $app->abort(404, 'This town building does not exist!');
        }

        if (!$app['user']->hasTownBuilding($townBuilding)) {
            $app->abort(403, 'This is not your town building!');
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
                        'The upgrade for the ":building:" building has started!',
                        array(
                            ':building:' => $townBuilding->getBuildingObject(),
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
            }
        }

        $referer = $request->headers->get('referer', false);
        if ($referer !== false) {
            return $app->redirect($referer);
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
    public function buildingsRepairAction($id, $buildingId, Application $app)
    {
        $town = $app['orm.em']->find(
            'Application\Entity\TownEntity',
            $id
        );

        if (!$town) {
            $app->abort(404, 'This town does not exist!');
        }

        $townBuilding = $app['orm.em']->find(
            'Application\Entity\TownBuildingEntity',
            $buildingId
        );

        if (!$townBuilding) {
            $app->abort(404, 'This town building does not exist!');
        }

        if (!$app['user']->hasTownBuilding($townBuilding)) {
            $app->abort(403, 'This is not your town building!');
        }

        $townBuilding->setHealthPoints(
            $townBuilding->getHealthPointsTotal()
        );

        $app['orm.em']->persist($townBuilding);

        // Substract the building bost
        $buildingResourcesCost = $townBuilding
            ->getBuildingObject()
            ->getResourcesCost(
                $townBuilding->getLevel()
            )
        ;
        $town->useResources($buildingResourcesCost);
        $app['orm.em']->persist($town);

        try {
            $hasEnoughResourcesForBuilding = $app['game.towns']
                ->hasEnoughResourcesForBuilding(
                    $town,
                    $townBuilding->getBuilding(),
                    $townBuilding->getLevel()
                )
            ;
            if (!$hasEnoughResourcesForBuilding) {
                throw new InsufficientResourcesException(
                    'You do not have enough resources to repair this building!'
                );
            }

            $app['orm.em']->flush();

            $app['flashbag']->add(
                'success',
                $app['translator']->trans(
                    'The building was successfully repaired!'
                )
            );
        } catch (\Exception $e) {
            $app['flashbag']->add(
                'danger',
                $e->getMessage()
            );
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
            $app->abort(404, 'This town does not exist!');
        }

        $townBuilding = $app['orm.em']->find(
            'Application\Entity\TownBuildingEntity',
            $buildingId
        );

        if (!$townBuilding) {
            $app->abort(404, 'This town building does not exist!');
        }

        if (!$app['user']->hasTownBuilding($townBuilding)) {
            $app->abort(403, 'This is not your town building!');
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
                    'town_id' => $townBuilding->getTown()->getId(),
                )
            )
        );
    }
}
