<?php

namespace Application\Game\Building;

use Application\Game\Resources;
use Application\Game\BuildingTypes;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Silos extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Silos')
            ->setKey('silos')
            ->setSlug('silos')
            ->setDescription('Stores food')
            ->setType(BuildingTypes::AGRICULTURAL)
            ->setSize('1x1')
            ->setMaximumLevel(2)
            ->setHealthPoints(array(
                0 => 1000,
                1 => 2000,
                2 => 3000,
            ))
            ->setPopulationCapacity(array(
                0 => 10,
                1 => 20,
                2 => 30,
            ))
            ->setResourcesCapacity(array(
                0 => array(
                    Resources::FOOD => 1000,
                ),
                1 => array(
                    Resources::FOOD => 2000,
                ),
                2 => array(
                    Resources::FOOD => 4000,
                ),
            ))
            ->setBuildTime(array(
                0 => 60,
                1 => 120,
                2 => 180,
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
                    Resources::WOOD => 300,
                    Resources::ROCK => 300,
                ),
            ))
        ;
    }
}