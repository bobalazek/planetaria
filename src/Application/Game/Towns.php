<?php

namespace Application\Game;

use Silex\Application;
use Application\Entity\TownEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Towns
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return boolean
     */
    public function hasEnoughResourcesForBuilding(TownEntity $town, $building)
    {
        $result = true;
        $buildingObject = Buildings::getAllWithData($building);
        $requiredResources = $buildingObject->getResourcesCost(0);
        $availableResources = $town->getResourcesAvailable();

        if (!empty($requiredResources)) {
            foreach ($requiredResources as $requiredResource => $requiredResourceValue) {
                if ($requiredResourceValue > $availableResources[$requiredResource]) {
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * @return boolean
     */
    public function hasReachedBuildingsLimit(TownEntity $town)
    {
        $townBuildingsCount = count($town->getTownBuildings());
        $townBuildingsLimit = $town->getBuildingsLimit();

        return $townBuildingsCount >= $townBuildingsLimit;
    }

    /**
     * @return void
     */
    public function updateTownResources(TownEntity $town)
    {
        $app = $this->app;
        $resources = $town->getResources();
        $townResources = $town->getTownResources();
        
        foreach ($resources as $resourceKey => $resourceData) {
            foreach ($townResources as $townResource) {
                if ($townResource->getResource() === $resourceKey) {
                    $resourceProduction = $resourceData['production'];
                    $townResourceAmount = $townResource->getAmount();
                    $resourcesProduced = 0;
                    
                    $townResource
                        ->setAmount($townResourceAmount + $resourcesProduced)
                    ;
                    
                    $app['orm.em']->persist($townResource);
                    continue;
                }
            }
        }
        
        $app['orm.em']->flush();
    }
}
