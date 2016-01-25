<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ApartmentBlock extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Apartment block')
            ->setKey('apartment_block')
            ->setSlug('apartment-block')
            ->setDescription('A apartment block will increase your population capacity.')
            ->setType(BuildingTypes::RESIDENTIAL)
            ->setSize('1x1')
            ->setMaximumLevel(2)
            ->setHealthPoints(array(
                0 => 5000,
                1 => 10000,
                2 => 20000,
            ))
            ->setPopulationCapacity(array(
                0 => 200,
                1 => 400,
                2 => 800,
            ))
            ->setResourcesCapacity(array(
                0 => 100,
                1 => 200,
                2 => 400,
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
