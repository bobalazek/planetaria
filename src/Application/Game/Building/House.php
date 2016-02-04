<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class House extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('House')
            ->setKey('house')
            ->setSlug('house')
            ->setDescription('A house it\'s a residential building that will increase your population limit.')
            ->setType(BuildingTypes::RESIDENTIAL)
            ->setSize('1x1')
            ->setMaximumLevel(2)
            ->setHealthPoints(array(
                0 => 1000,
                1 => 2000,
                2 => 4000,
            ))
            ->setPopulationCapacity(array(
                0 => 20,
                1 => 40,
                2 => 80,
            ))
            ->setResourcesCapacity(array(
                0 => 10,
                1 => 20,
                2 => 40,
            ))
            ->setBuildTime(array(
                0 => 10,
                1 => 20,
                2 => 30,
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
                    Resources::WOOD => 400,
                    Resources::ROCK => 400,
                ),
            ))
            ->setAvailableTerrainTypes(array(
                TileTerrainTypes::GRASSLAND,
                TileTerrainTypes::DESERT,
            ))
        ;
    }
}
