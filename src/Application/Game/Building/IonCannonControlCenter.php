<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class IonCannonControlCenter extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Ion cannon control center')
            ->setKey('ion_cannon_control_center')
            ->setSlug('ion-cannon-control-center')
            ->setDescription('A super strong weapon, that pulverizes everything is shoots.')
            ->setType(BuildingTypes::MILITARY)
            ->setSize('1x1')
            ->setMaximumLevel(0)
            ->setHealthPoints(array(
                0 => 10000,
            ))
            ->setPopulationCapacity(array(
                0 => 50,
            ))
            ->setResourcesCapacity(array(
                0 => 100,
            ))
            ->setBuildTime(array(
                0 => 3600,
            ))
            ->setResourcesCost(array(
                0 => array(
                    Resources::WOOD => 200000,
                    Resources::ROCK => 200000,
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
