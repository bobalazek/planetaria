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
     * @param TownEntity $town
     * @param boolean    $ignoreCapacityLimit Should it ignore  the capacity imit (and skipp the setter to capacity, if it's more)? Usefull when creating a new town, that does not have any storage yet (because it doesn't have any buildings that would increase it)
     *
     * @return void
     */
    public function updateTownResources(TownEntity $town, $ignoreCapacityLimit = false)
    {
        $app = $this->app;
        $resources = $town->getResources();
        $townResources = $town->getTownResources();
        $currentDatetime = new \Datetime();
        $townLastUpdatedResources = $town->getTimeLastUpdatedResources();
        if ($townLastUpdatedResources === null) {
            $townLastUpdatedResources = new \Datetime();
        }
        $differenceSeconds = strtotime($currentDatetime->format(DATE_ATOM)) - strtotime($townLastUpdatedResources->format(DATE_ATOM));

        foreach ($resources as $resourceKey => $resourceData) {
            foreach ($townResources as $townResource) {
                if ($townResource->getResource() === $resourceKey) {
                    $resourceProduction = $resourceData['production'];
                    $townResourceAmount = $townResource->getAmount();
                    $resourcesProduced = ($resourceProduction / 60) * $differenceSeconds;
                    $amount = $townResourceAmount + $resourcesProduced;

                    // Nothing done - skip it, instead of updating it!
                    if ($resourcesProduced === 0) {
                        break;
                    }

                    if (
                        !$ignoreCapacityLimit &&
                        $amount > $resourceData['capacity'] &&
                        $resourceData['capacity'] !== -1 // -1 capacity means inifinitve, so ignore it!
                    ) {
                        $amount = $resourceData['capacity'];
                    }

                    // If nothing has changed, not NOT update it!
                    // Note: Do NOT exactly compare (===) because capacity is an integer and the amount is a float (that would make this statement always invalid, if the amount is set by the capacity)!
                    if ($amount == $townResourceAmount) {
                        break;
                    }

                    $townResource
                        ->setAmount($amount)
                    ;

                    $app['orm.em']->persist($townResource);
                    break;
                }
            }
        }

        $town
            ->setTimeLastUpdatedResources($currentDatetime)
        ;

        $app['orm.em']->persist($town);

        $app['orm.em']->flush();

        // Reload the town entity, so we have the newest information available!
        $app['orm.em']->refresh($town);
    }
}
