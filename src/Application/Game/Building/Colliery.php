<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Colliery extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Colliery')
            ->setKey('colliery')
            ->setDescription('With colliery you can obtain coal from the ground.')
            ->setType(BuildingTypes::INDUSTRIAL)
            ->setSize('1x1')
            ->setMaximumLevel(5)
            ->setHealthPoints(array(
                0 => 1000,
                1 => 2000,
                2 => 3000,
                3 => 4000,
                4 => 5000,
                5 => 6000,
            ))
            ->setPopulationCapacity(array(
                0 => 10,
                1 => 20,
                2 => 30,
                3 => 40,
                4 => 50,
                5 => 60,
            ))
            ->setResourcesCapacity(array(
                0 => 1000,
                1 => 2000,
                2 => 3000,
                3 => 4000,
                4 => 5000,
                5 => 6000,
            ))
            ->setBuildTime(array(
                0 => 60,
                1 => 120,
                2 => 180,
                3 => 240,
                4 => 300,
                5 => 360,
            ))
            ->setResourcesCost(array(
                0 => array(
                    Resources::WOOD => 100,
                    Resources::ROCK => 100,
                ),
                1 => array(
                    Resources::WOOD => 200,
                    Resources::ROCK => 200,
                ),
                2 => array(
                    Resources::WOOD => 300,
                    Resources::ROCK => 300,
                ),
                3 => array(
                    Resources::WOOD => 400,
                    Resources::ROCK => 400,
                ),
                4 => array(
                    Resources::WOOD => 500,
                    Resources::ROCK => 500,
                ),
                5 => array(
                    Resources::WOOD => 600,
                    Resources::ROCK => 600,
                ),
            ))
            ->setResourcesProduction(array(
                0 => array(
                    Resources::COAL => 10,
                ),
                1 => array(
                    Resources::COAL => 20,
                ),
                2 => array(
                    Resources::COAL => 30,
                ),
                3 => array(
                    Resources::COAL => 40,
                ),
                4 => array(
                    Resources::COAL => 50,
                ),
                5 => array(
                    Resources::COAL => 60,
                ),
            ))
            ->setAvailableTerrainTypes(array(
                TileTerrainTypes::GRASSLAND,
                TileTerrainTypes::DESERT,
            ))
        ;
    }
}
