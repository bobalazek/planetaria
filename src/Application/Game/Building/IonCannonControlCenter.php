<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;
use Application\Game\Weapons;

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
            ->setDescription('Ion Cannon control center is a facility that can interact and send orders to Ion Cannon weapon.')
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
            ->setWeaponsProduction(array(
                0 => array(
                    Weapons::ION_CANNON_SATELITE,
                ),
            ))
            ->setAvailableTerrainTypes(array(
                TileTerrainTypes::GRASSLAND,
                TileTerrainTypes::DESERT,
            ))
        ;
    }
}
