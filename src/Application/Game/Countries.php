<?php

namespace Application\Game;

use Silex\Application;
use Application\Entity\CountryEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Countries
{
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * How many of this buildings are there in this country?
     *
     * @param CountryEntity $country
     * @param string        $building
     *
     * @return boolean
     */
    public function getBuildingsCount(CountryEntity $country, $building)
    {
        $app = $this->app;

        $thisBuildingCount = 0;
        $towns = $country->getTowns();

        if (!empty($towns)) {
            foreach ($towns as $town) {
                $thisBuildingCount += $app['game.towns']->getBuildingsCount($town, $building);
            }
        }

        return $thisBuildingCount;
    }

    /**
     * Has the country reached the total limit for that one specific building?
     *
     * @param CountryEntity $country
     * @param string        $building
     *
     * @return boolean
     */
    public function hasReachedBuildingPerCountryLimit(CountryEntity $country, $building)
    {
        $buildingObject = Buildings::getAllWithData($building);
        $buildingObjectPerCountryLimit = $buildingObject->getPerCountryLimit();

        if ($buildingObjectPerCountryLimit === -1) {
            return false;
        }

        $thisBuildingCount = $this->getBuildingsCount($country, $building);

        return $thisBuildingCount >= $buildingObjectPerCountryLimit;
    }
}
