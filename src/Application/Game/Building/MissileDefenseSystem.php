<?php

namespace Application\Game\Building;

use Application\Game\Buildings;
use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class MissileDefenseSystem extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Missile defense system')
            ->setKey('missile_defense_system')
            ->setDescription('A missile defense system can shield your town, in case you are under missile attack.')
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
                    Resources::WOOD => 20000,
                    Resources::ROCK => 20000,
                ),
            ))
            ->setBuildingsRequired(array(
                0 => array(
                    Buildings::AIRBASE => 0,
                ),
            ))
            ->setAvailableTerrainTypes(array(
                TileTerrainTypes::GRASSLAND,
                TileTerrainTypes::DESERT,
            ))
        ;
    }
}
