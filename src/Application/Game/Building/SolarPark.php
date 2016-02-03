<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class SolarPark extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Solar park')
            ->setKey('solar_park')
            ->setSlug('solar-park')
            ->setDescription('You can produce electricity with it.')
            ->setType(BuildingTypes::INDUSTRIAL)
            ->setSize('1x1')
            ->setMaximumLevel(2)
            ->setHealthPoints(array(
                0 => 1000,
                1 => 2000,
                2 => 3000,
            ))
            ->setPopulationCapacity(array(
                0 => 10,
                1 => 20,
                2 => 30,
            ))
            ->setResourcesCapacity(array(
                0 => 1000,
                1 => 2000,
                2 => 3000,
            ))
            ->setBuildTime(array(
                0 => 60,
                1 => 120,
                2 => 180,
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
            ))
            ->setResourcesProduction(array(
                0 => array(
                    Resources::ELECTRICITY => 10,
                ),
                1 => array(
                    Resources::ELECTRICITY => 20,
                ),
                2 => array(
                    Resources::ELECTRICITY => 30,
                ),
            ))
            ->setAvailableTerrainTypes(array(
                TileTerrainTypes::GRASSLAND,
                TileTerrainTypes::PLAINS,
                TileTerrainTypes::DESERT,
            ))
        ;
    }
}
