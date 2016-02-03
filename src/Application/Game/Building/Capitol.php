<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Capitol extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Capitol')
            ->setKey('capitol')
            ->setSlug('capitol')
            ->setDescription('The main town building.')
            ->setUserExperiencePoints(200)
            ->setType(BuildingTypes::GOVERNMENT)
            ->setSize('2x2')
            ->setMaximumLevel(3)
            ->setPerTownLimit(1)
            ->setHealthPoints(array(
                0 => 10000,
                1 => 20000,
                2 => 40000,
                3 => 80000,
            ))
            ->setPopulationCapacity(array(
                0 => 50,
                1 => 100,
                2 => 200,
                3 => 400,
            ))
            ->setResourcesCapacity(array(
                0 => 4000,
                1 => 8000,
                2 => 12000,
                3 => 16000,
            ))
            ->setBuildTime(array(
                0 => 0,
                1 => 30,
                2 => 60,
                3 => 120,
            ))
            ->setResourcesCost(array(
                0 => array(
                    // It doesn't cost anything, so we can build it as the base building.
                ),
                1 => array(
                    // Should be more as the building resources capacity,
                    // so the players don't "accidentially" buy the next level,
                    // but then have nothing left for other production buildings.
                    Resources::WOOD => 6000,
                    Resources::ROCK => 6000,
                ),
                2 => array(
                    Resources::WOOD => 12000,
                    Resources::ROCK => 12000,
                ),
                3 => array(
                    Resources::WOOD => 18000,
                    Resources::ROCK => 18000,
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
