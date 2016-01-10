<?php

namespace Application\Game\Building;

use Application\Game\Resource\Resources;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Dock extends AbstractBuilding
{
    /**
     * The constructor
     */
    public function __construct()
    {
        $this
            ->setName('Dock')
            ->setKey('dock')
            ->setSlug('dock')
            ->setDescription('With a airbase you can produce water units.')
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
            ->setStorageCapacity(array(
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
        ;
    }
}
