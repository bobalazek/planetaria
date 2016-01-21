<?php

namespace Application\Game;

use Silex\Application;
use Application\Entity\UserEntity;
use Application\Entity\CountryEntity;
use Application\Entity\UserCountryEntity;
use Application\Entity\TownEntity;
use Application\Entity\PlanetEntity;
use Application\Game\CountryRoles;
use Application\Game\Buildings;

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
    
    /**
     * @param UserEntity   $user
     * @param array        $country
     * @param array        $town
     * @param PlanetEntity $planet
     * @param array        $startCoordinates
     *
     * @return boolean
     */
    public function prepareNew($user, $country, $town, $planet, array $startCoordinates)
    {
        $app = $this->app;
        $startCoordinatesX = $startCoordinates[0];
        $startCoordinatesY = $startCoordinates[0];
        
        $user = $app['orm.em']->find('Application\Entity\UserEntity', 1);
        $app['user'] = $user;

        // Country
        $countryEntity = new CountryEntity();
        $countryEntity
            ->setId(1)
            ->setName($country['name'])
            ->setSlug($country['slug'])
            ->setDescription($country['description'])
            ->setUser($user)
        ;
        $app['orm.em']->persist($countryEntity);

        // Town
        $townEntity = new TownEntity();
        $townEntity
            ->setId(1)
            ->setName($town['name'])
            ->setSlug($town['slug'])
            ->setDescription($town['description'])
            ->setPlanet($planet)
            ->setUser($user)
            ->setCountry($countryEntity)
            ->prepareTownResources(10000)
        ;
        $app['orm.em']->persist($townEntity);

        // User country
        // To-Do: Think abut, if we will maybe use this.
        $userCountryEntity = new UserCountryEntity();
        $userCountryEntity
            ->setId(1)
            ->setRoles(array(
                CountryRoles::CREATOR,
                CountryRoles::OWNER,
            ))
            ->setCountry($countryEntity)
            ->setUser($user)
        ;
        $app['orm.em']->persist($userCountryEntity);

        // Save them, because we'll need them soon!
        $app['orm.em']->flush();

        // Town building - Capitol
        $app['game.buildings']->build(
            $planet,
            $townEntity,
            array($startCoordinatesX, $startCoordinatesY),
            Buildings::CAPITOL,
            true // $ignoreCapacityLimit
        );

        // Town building - Farms
        $farmsCoordinates = array(
            array($startCoordinatesX-1, $startCoordinatesY-1),
            array($startCoordinatesX+2, $startCoordinatesY-1),
            array($startCoordinatesX-1, $startCoordinatesY+2),
            array($startCoordinatesX+2, $startCoordinatesY+2),
        );
        foreach ($farmsCoordinates as $farmCoordinates) {
            $app['game.buildings']->build(
                $planet,
                $townEntity,
                $farmCoordinates,
                Buildings::FARM,
                true // $ignoreCapacityLimit
            );
        }
    }
}
