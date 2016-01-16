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
    public static function hasEnoughResourcesForBuilding(TownEntity $town, $building)
    {
        $requiredResources = array();
        $availableResources = array();

        return true;
    }

    /**
     * @return void
     */
    public static function updateTownResources(TownEntity $town)
    {
        // To-Do
    }
}
