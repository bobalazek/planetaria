<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Airbase extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Airbase')
            ->setKey('airbase')
            ->setDescription('An airbase is a military aerodrome used for military aircrafts.')
            ->setType(BuildingTypes::MILITARY)
            ->setSize('2x2')
            ->setMaximumLevel(2)
            ->setHealthPoints(array(
                0 => 10000,
                1 => 20000,
                2 => 40000,
            ))
            ->setPopulationCapacity(array(
                0 => 100,
                1 => 200,
                2 => 400,
            ))
            ->setResourcesCapacity(array(
                0 => 1000,
                1 => 2000,
                2 => 4000,
            ))
            ->setBuildTime(array(
                0 => 60,
                1 => 300,
                2 => 600,
            ))
            ->setResourcesCost(array(
                0 => array(
                    Resources::WOOD => 1000,
                    Resources::ROCK => 1000,
                ),
                1 => array(
                    Resources::WOOD => 2000,
                    Resources::ROCK => 2000,
                ),
                2 => array(
                    Resources::WOOD => 4000,
                    Resources::ROCK => 4000,
                ),
            ))
            ->setAvailableTerrainTypes(array(
                TileTerrainTypes::GRASSLAND,
                TileTerrainTypes::DESERT,
            ))
        ;
    }
}
