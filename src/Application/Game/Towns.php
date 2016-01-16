<?php

namespace Application\Game;

use Silex\Application;
use Application\Entity\TownEntity;
use Application\Game\Buildings;

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
    public static function hasEnoughResourcesForBuilding(TownEntity $town, $building)
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
     * @return void
     */
    public static function updateTownResources(TownEntity $town)
    {
        // To-Do
    }
}
