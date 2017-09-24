<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;
use Application\Game\TileTerrainTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Market extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Market')
            ->setKey('market')
            ->setDescription('Market provides economic improvement in a game. You can exchange one resource for another.')
            ->setType(BuildingTypes::COMMERCIAL)
            ->setSize('1x1')
            ->setMaximumLevel(0)
            ->setPerTownLimit(1)
            ->setHealthPoints(array(
                0 => 2000,
            ))
            ->setPopulationCapacity(array(
                0 => 10,
            ))
            ->setResourcesCapacity(array(
                0 => 1000,
            ))
            ->setBuildTime(array(
                0 => 60,
            ))
            ->setResourcesCost(array(
                0 => array(
                    Resources::WOOD => 1000,
                    Resources::ROCK => 1000,
                ),
            ))
            ->setAvailableTerrainTypes(array(
                TileTerrainTypes::GRASSLAND,
                TileTerrainTypes::DESERT,
            ))
        ;
    }
}
