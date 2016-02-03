<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;
use Application\Game\Units;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Barracks extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Barracks')
            ->setKey('barracks')
            ->setSlug('barracks')
            ->setDescription('With a barracks you can produce land units.')
            ->setType(BuildingTypes::MILITARY)
            ->setSize('1x1')
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
            ->setUnitsProduction(array(
                0 => array(
                    Units::RIFLEMAN,
                ),
                1 => array(
                    Units::RIFLEMAN,
                ),
                2 => array(
                    Units::RIFLEMAN,
                ),
            ))
            ->setAvailableTerrainTypes(array(
                TileTerrainTypes::GRASSLAND,
                TileTerrainTypes::DESERT,
            ))
        ;
    }
}
