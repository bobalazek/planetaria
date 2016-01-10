<?php

namespace Application\Game\Building;

use Application\Game\Resource\Resources;

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
            ->setSlug('market')
            ->setDescription('With a market, you will be able to exchange resources and items.')
            ->setType(BuildingTypes::CIVIL)
            ->setSize('1x1')
            ->setMaximumLevel(0)
            ->setHealthPoints(array(
                0 => 2000,
            ))
            ->setPopulationCapacity(array(
                0 => 10,
            ))
            ->setStorageCapacity(array(
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
        ;
    }
}
