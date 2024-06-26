<?php

namespace Application\Game;

use Silex\Application;
use Application\Entity\TownEntity;
use Application\Entity\TownResourceEntity;

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
     * Check if the town has enough resources to construct this building.
     *
     * @param TownEntity $town
     * @param string     $building
     * @param integer    $level
     *
     * @return boolean
     */
    public function hasEnoughResourcesForBuilding(TownEntity $town, $building, $level = 0)
    {
        $buildingObject = Buildings::getAllWithData($building);
        $requiredResources = $buildingObject->getResourcesCost($level);
        $availableResources = $town->getResourcesAvailable();

        if (!empty($requiredResources)) {
            foreach ($requiredResources as $requiredResource => $requiredResourceValue) {
                if ($requiredResourceValue > $availableResources[$requiredResource]) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check if the town hat the required buildings to construct this building.
     *
     * @param TownEntity $town
     * @param string     $building
     * @param integer    $level
     *
     * @return boolean
     */
    public function hasRequiredBuildingsForBuilding(TownEntity $town, $building, $level = 0)
    {
        $buildingObject = Buildings::getAllWithData($building);
        $requiredBuildings = $buildingObject->getBuildingsRequired($level);
        $townBuildings = $town->getTownBuildings();
        $townBuildingsArray = array();

        // Find all the building (and it's max level)
        if (!empty($townBuildings)) {
            foreach ($townBuildings as $townBuilding) {
                $key = $townBuilding->getBuilding();
                $level = $townBuilding->getLevel();

                if (!isset($townBuildingsArray[$key])) {
                    $townBuildingsArray[$key] = $level;
                }

                if ($townBuildingsArray[$key] > $level) {
                    $townBuildingsArray[$key] = $level;
                }
            }
        }

        if (!empty($requiredBuildings)) {
            foreach ($requiredBuildings as $requiredBuilding => $requiredBuildingLevel) {
                // If we don't have that required building
                if (!isset($townBuildingsArray[$requiredBuilding])) {
                    return false;
                }

                // If the building we have, has the required level
                $buildingMaximumLevel = $townBuildingsArray[$requiredBuilding];
                if ($buildingMaximumLevel < $requiredBuildingLevel) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Has the town reached the total buildings limit for that town?
     *
     * @param TownEntity $town
     *
     * @return boolean
     */
    public function hasReachedTownBuildingsLimit(TownEntity $town)
    {
        $townBuildingsCount = count($town->getTownBuildings());
        $townBuildingsLimit = $town->getBuildingsLimit();

        return $townBuildingsCount >= $townBuildingsLimit;
    }

    /**
     * Has the town reached the total limit for that one specific building?
     *
     * @param TownEntity $town
     * @param string     $building
     *
     * @return boolean
     */
    public function hasReachedBuildingPerTownLimit(TownEntity $town, $building)
    {
        $buildingObject = Buildings::getAllWithData($building);
        $buildingObjectPerTownLimit = $buildingObject->getPerTownLimit();

        if ($buildingObjectPerTownLimit === -1) {
            return false;
        }

        $thisBuildingCount = $this->getBuildingsCount($town, $building);

        return $thisBuildingCount >= $buildingObjectPerTownLimit;
    }

    /**
     * How many of this buildings are there in this town?
     *
     * @param TownEntity $town
     * @param string     $building
     *
     * @return boolean
     */
    public function getBuildingsCount(TownEntity $town, $building)
    {
        $thisBuildingCount = 0;
        $townBuildings = $town->getTownBuildings();

        foreach ($townBuildings as $townBuilding) {
            if ($townBuilding->getBuilding() === $building) {
                $thisBuildingCount++;
            }
        }

        return $thisBuildingCount;
    }

    /**
     * How many of this buildings are there in this town?
     *
     * @param TownEntity $town
     * @param string     $building
     *
     * @return boolean
     */
    public function isInsideRadius(TownEntity $town, $startingCoordinates)
    {
        $app = $this->app;
        $buildRadius = $app['gameOptions']['townBuildRadius'];
        $x1 = $startingCoordinates[0];
        $y1 = $startingCoordinates[1];
        $x2 = $town->getCoordinatesX();
        $y2 = $town->getCoordinatesY();

        // That means, that we couln't fetched the capitol building coordinates,
        // which is the town center... means, no capitol yet...
        // You can build wherever you want!
        if ($x2 === false || $y2 === false) {
            return true;
        }

        $distance = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));

        if ($distance > $buildRadius) {
            return false;
        }

        return true;
    }

    /**
     * @param TownEntity $town
     *
     * @return void
     */
    public function updateTownResources(TownEntity $town)
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

        if (empty($townResources)) {
            $town->prepareTownResources();
            $app['orm.em']->persist($town);
            // Once the resources are prepared, fetch them!
            $townResources = $town->getTownResources();
        }

        foreach ($townResources as $i => $townResource) {
            $resourceKey = $townResource->getResource();
            $townResources[$resourceKey] = $townResource;
            unset($townResources[$i]);
        }

        foreach ($resources as $resourceKey => $resourceData) {
            // If a resource does not exist, create it!
            if (!isset($townResources[$resourceKey])) {
                $townResource = new TownResourceEntity();
                $townResource
                    ->setResource($resourceKey)
                    ->setTown($town)
                ;
                $app['orm.em']->persist($townResource);
            } else {
                $townResource = $townResources[$resourceKey];
            }

            $resourceProduction = $resourceData['production'];
            $townResourceAmount = $townResource->getAmount();
            $resourcesProduced = ($resourceProduction / 60) * $differenceSeconds;
            $amount = $townResourceAmount + $resourcesProduced;

            if (
                $amount > $resourceData['capacity'] &&
                $resourceData['capacity'] !== -1 // -1 capacity means inifinitve, so ignore it!
            ) {
                $amount = $resourceData['capacity'];
            }

            // If nothing has changed, not NOT update it!
            // Note: Do NOT exactly compare (===) because capacity is an integer and the amount is a float (that would make this statement always invalid, if the amount is set by the capacity)!
            if ($amount == $townResourceAmount) {
                continue;
            }

            $townResource->setAmount($amount);
            $app['orm.em']->persist($townResource);
        }

        $town->setTimeLastUpdatedResources($currentDatetime);

        $app['orm.em']->persist($town);

        $app['orm.em']->flush();

        // Reload the town entity, so we have the newest information available!
        $app['orm.em']->refresh($town);
    }

    /**
     * @param TownEntity $town
     *
     * @return void
     */
    public function checkForFinishedBuildingUpgrades(TownEntity $town)
    {
        $app = $this->app;
        $townBuildings = $town->getTownBuildings();

        if (!empty($townBuildings)) {
            $currentDatetime = new \Datetime();

            foreach ($townBuildings as $townBuilding) {
                $townBuildingTimeNextLevelUpgradeStarted = $townBuilding->getTimeNextLevelUpgradeStarted();
                $townBuildingTimeNextLevelUpgradeEnds = $townBuilding->getTimeNextLevelUpgradeEnds();

                if (
                    $townBuildingTimeNextLevelUpgradeStarted !== null &&
                    $townBuildingTimeNextLevelUpgradeEnds !== null &&
                    $currentDatetime > $townBuildingTimeNextLevelUpgradeEnds
                ) {
                    $buildingObject = $townBuilding->getBuildingObject();
                    $nextLevel = $townBuilding->getNextLevel();
                    $healthPoints = $buildingObject->getHealthPoints($nextLevel) * ($townBuilding->getHealthPointsPercentage() / 100);

                    $townBuilding
                        ->setHealthPoints($healthPoints)
                        ->setLevel($nextLevel)
                        ->setTimeNextLevelUpgradeStarted(null)
                        ->setTimeNextLevelUpgradeEnds(null)
                    ;

                    $app['orm.em']->persist($townBuilding);

                    $app['orm.em']->flush();

                    $app['orm.em']->refresh($townBuilding);
                }
            }
        }
    }
}
