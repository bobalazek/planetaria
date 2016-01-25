<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Warehouse extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Warehouse')
            ->setKey('warehouse')
            ->setSlug('warehouse')
            ->setDescription('A warehouse will increase your storage capacity.')
            ->setType(BuildingTypes::COMMERCIAL)
            ->setSize('1x1')
            ->setMaximumLevel(2)
            ->setHealthPoints(array(
                0 => 2000,
                1 => 4000,
                2 => 8000,
            ))
            ->setPopulationCapacity(array(
                0 => 10,
                1 => 20,
                2 => 40,
            ))
            ->setResourcesCapacity(array(
                0 => 1000,
                1 => 2000,
                2 => 4000,
            ))
            ->setBuildTime(array(
                0 => 60,
                1 => 120,
                2 => 300,
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
                TerrainTypes::GRASSLAND,
                TerrainTypes::PLAINS,
                TerrainTypes::DESERT,
            ))
        ;
    }
}
