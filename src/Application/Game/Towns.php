<?php

namespace Application\Game;

use Silex\Application;
use Doctrine\Common\Util\Inflector;
use Application\Entity\TownEntity;
use Application\Entity\PlanetEntity;
use Application\Entity\TownBuildingEntity;

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
        // To-Do: check!

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
