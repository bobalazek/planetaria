<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;

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
            ->setType(BuildingTypes::CIVIL)
            ->setSize('2x2')
            ->setMaximumLevel(3)
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
                0 => 100,
                1 => 200,
                2 => 400,
                3 => 800,
            ))
            ->setBuildTime(array(
                0 => 0,
                1 => 30,
                2 => 60,
                3 => 120,
            ))
            ->setResourcesCost(array(
                0 => array(
                    Resources::WOOD => 2000,
                    Resources::ROCK => 2000,
                ),
                1 => array(
                    Resources::WOOD => 4000,
                    Resources::ROCK => 4000,
                ),
                2 => array(
                    Resources::WOOD => 8000,
                    Resources::ROCK => 8000,
                ),
                3 => array(
                    Resources::WOOD => 16000,
                    Resources::ROCK => 16000,
                ),
            ))
        ;
    }
}
