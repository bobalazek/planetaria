<?php

namespace Application\Game;

use Silex\Application;
use Doctrine\Common\Util\Inflector;
use Application\Entity\TownEntity;
use Application\Entity\PlanetEntity;
use Application\Entity\TownBuildingEntity;
use Application\Game\Exception\InsufficientResourcesException;
use Application\Game\Exception\InsufficientAreaSpaceException;
use Application\Game\Exception\TownBuildingsLimitReachedException;
use Application\Game\Exception\TownBuildingAlreadyUpgradingException;
use Application\Game\Exception\TownBuildingNotUpgradableException;
use Application\Game\Exception\TownBuildingInConstructionException;
use Application\Game\Exception\TownBuildingAtMaximumLevelException;
use Application\Game\Exception\MissingRequiredBuildingsException;
use Application\Game\Exception\BuildingPerTownLimitReachedException;
use Application\Game\Exception\BuildingPerCountryLimitReachedException;
use Application\Game\Exception\BuildingNotInsideTownRadius;
use Application\Game\Exception\BuildingNotBuildableOnThisTerrain;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Buildings
{
    /**
     * @var string
     */
    const CAPITOL = 'capitol';

    /**
     * @var string
     */
    const HOUSE = 'house';

    /**
     * @var string
     */
    const FARM = 'farm';

    /**
     * @var string
     */
    const SILOS = 'silos';

    /**
     * @var string
     */
    const APARTMENT_BLOCK = 'apartment_block';

    /**
     * @var string
     */
    const MARKET = 'market';

    /**
     * @var string
     */
    const WAREHOUSE = 'warehouse';

    /**
     * @var string
     */
    const BANK = 'bank';

    /**
     * @var string
     */
    const PUMPJACK = 'pumpjack';

    /**
     * @var string
     */
    const QUARRY = 'quarry';

    /**
     * @var string
     */
    const LOGGING_CAMP = 'logging_camp';

    /**
     * @var string
     */
    const COLLIERY = 'colliery';

    /**
     * @var string
     */
    const IRON_MINE = 'iron_mine';

    /**
     * @var string
     */
    const NUCLEAR_POWER_PLANT = 'nuclear_power_plant';

    /**
     * @var string
     */
    const SOLAR_PARK = 'solar_park';

    /**
     * @var string
     */
    const WIND_FARM = 'wind_farm';

    /**
     * @var string
     */
    const HOSPITAL = 'hospital';

    /**
     * @var string
     */
    const AIRBASE = 'airbase';

    /**
     * @var string
     */
    const BARRACKS = 'barracks';

    /**
     * @var string
     */
    const MISSILE_LAUNCH_FACILITY = 'missile_launch_facility';

    /**
     * @var string
     */
    const MISSILE_DEFENSE_SYSTEM = 'missile_defense_system';

    /**
     * @var string
     */
    const ION_CANNON_CONTROL_CENTER = 'ion_cannon_control_center';

    /**
     * @var string
     */
    const ION_CANNON_DEFENSE_SYSTEM = 'ion_cannon_defense_system';

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
     * @return array
     */
    public static function getAll($key = null)
    {
        $all = array(
            self::CAPITOL => 'Capitol',
            self::HOUSE => 'House',
            self::APARTMENT_BLOCK => 'Apartment block',
            self::MARKET => 'Market',
            self::WAREHOUSE => 'Warehouse',
            self::BANK => 'Bank',
            self::FARM => 'Farm',
            self::SILOS => 'Silos',
            self::PUMPJACK => 'Pumpjack',
            self::QUARRY => 'Quarry',
            self::LOGGING_CAMP => 'Logging camp',
            self::COLLIERY => 'Colliery',
            self::IRON_MINE => 'Iron mine',
            self::NUCLEAR_POWER_PLANT => 'Nuclear power plant',
            self::SOLAR_PARK => 'Solar park',
            self::WIND_FARM => 'Wind farm',
            self::HOSPITAL => 'Hospital',
            self::BARRACKS => 'Barracks',
            self::AIRBASE => 'Airbase',
            self::MISSILE_LAUNCH_FACILITY => 'Missile launch facility',
            self::MISSILE_DEFENSE_SYSTEM => 'Missile defense system',
            self::ION_CANNON_CONTROL_CENTER => 'Ion cannon control center',
            self::ION_CANNON_DEFENSE_SYSTEM => 'Ion cannon defense system',
        );

        return $key === null
            ? $all
            : $all[$key]
        ;
    }

    /**
     * @return string
     */
    public static function getClassName($key)
    {
        return Inflector::classify($key);
    }

    /**
     * @return array
     */
    public static function getAllWithData($key = null)
    {
        $buildings = self::getAll();

        foreach ($buildings as $building => $buildingName) {
            $className = 'Application\\Game\\Building\\'.self::getClassName($building);
            $buildingObject = new $className();

            if (
                $key !== null &&
                $key === $building
            ) {
                return $buildingObject;
            }

            $buildings[$building] = $buildingObject;
        }

        return $buildings;
    }

    /******************** Build ********************/
    /**
     * With this method we'll create the town building.
     *
     * @param PlanetEntity  $planet
     * @param TownEntity    $town
     * @param array|boolean $startingCoordinates The start coordinates (bottom left) of the location that building is going to be build. If it's not an array, then it won't do any coordinate related tests.
     * @param string        $building
     * @param string        $buildingStatus
     *
     * @return TownBuildingEntity
     */
    public function build(
        PlanetEntity $planet,
        TownEntity $town,
        $startingCoordinates = array(),
        $building
    ) {
        $app = $this->app;

        // Before the buy, update the town resources (storage) to the current state.
        $app['game.towns']->updateTownResources($town);

        /***** Checks *****/
        $this->doPreBuildChecks(
            $planet,
            $town,
            $startingCoordinates,
            $building
        );

        // Save the town building
        $buildingObject = $this->getAllWithData($building);
        $buildTimeSeconds = $buildingObject->getBuildTime(0); // How long does the initial level take to build?
        $timeConstructed = new \Datetime();
        $timeConstructed->add(new \DateInterval('PT'.$buildTimeSeconds.'S'));
        $townBuildingEntity = new TownBuildingEntity();
        $townBuildingEntity
            ->setBuilding($building)
            ->setTown($town)
            ->setHealthPoints($buildingObject->getHealthPoints(0))
            ->setTimeConstructed($timeConstructed)
        ;
        $app['orm.em']->persist($townBuildingEntity);

        // Save the building on that tiles
        $requiredTiles = $this->getRequiredTiles($planet, $startingCoordinates, $building);
        foreach ($requiredTiles as $requiredTile) {
            $buildingSectionCoordinates = $this->getBuildingSectionCoordinates(
                array(
                    $requiredTile->getCoordinatesX(),
                    $requiredTile->getCoordinatesY(),
                ),
                $startingCoordinates,
                $building
            );

            if ($buildingSectionCoordinates === false) {
                throw new \Exception(
                    'Could not get building section coordinates!'
                );
            }
            $buildingSectionX = $buildingSectionCoordinates[0];
            $buildingSectionY = $buildingSectionCoordinates[1];

            $requiredTile
                ->setTownBuilding($townBuildingEntity)
                ->setBuildingSection($buildingSectionX.'x'.$buildingSectionY)
            ;
            $app['orm.em']->persist($requiredTile);
        }

        // Substract the resources in the town for that building
        $buildingResourcesCost = $buildingObject->getResourcesCost(0);
        $town->useResources($buildingResourcesCost);
        $app['orm.em']->persist($town);

        // Add expericence point to the user
        if ($app['user']) {
            $experiencePoints = $buildingObject->getUserExperiencePoints(0);

            $app['user']->addExperiencePoints($experiencePoints);

            $app['orm.em']->persist($app['user']);
        }

        // Fully hydrate the town resources when the first building (Capitol) is built.
        if (
            empty($town->getTownBuildings()) &&
            $building == Buildings::CAPITOL
        ) {
            $town->prepareTownResources(
                $buildingObject->getResourcesCapacity(0)
            );

            $app['orm.em']->persist($town);
        }

        // Save everything
        $app['orm.em']->flush();

        // Check if the user has earned any new badges
        $app['game']->badgesCheck();

        return $townBuildingEntity;
    }

    /**
     * With this method we'll do checks if everyting fits before the building is being build.
     *
     * @param PlanetEntity  $planet
     * @param TownEntity    $town
     * @param array|boolean $startingCoordinates The start coordinates (bottom left) of the location that building is going to be build. If it's not an array, then it won't do any coordinate related tests.
     * @param string        $building
     * @param string        $buildingStatus
     *
     * @return void
     * @throws TownBuildingsLimitReachedException
     * @throws BuildingPerTownLimitReachedException
     * @throws BuildingPerCountryLimitReachedException
     * @throws MissingRequiredBuildingsException
     * @throws InsufficientResourcesException
     * @throws InsufficientAreaSpaceException
     */
    public function doPreBuildChecks(
        PlanetEntity $planet,
        TownEntity $town,
        $startingCoordinates,
        $building
    ) {
        $app = $this->app;

        /***** Town checks *****/
        // Check if we have reached the buildings limit (for that town)
        $hasReachedTownBuildingsLimit = $app['game.towns']
            ->hasReachedTownBuildingsLimit($town)
        ;
        if ($hasReachedTownBuildingsLimit) {
            throw new TownBuildingsLimitReachedException(
                'You have reached the buildings limit for this town!'
            );
        }

        /***** Building - Limit checks *****/
        /*** Per Town ***/
        // Check if we have reached the buildings limit (for that building)
        $hasReachedBuildingPerTownLimit = $app['game.towns']
            ->hasReachedBuildingPerTownLimit(
                $town,
                $building
            )
        ;
        if ($hasReachedBuildingPerTownLimit) {
            throw new BuildingPerTownLimitReachedException(
                'You have reached this buildings limit for this town!'
            );
        }

        /*** Per Country ***/
        // Check if we have reached the buildings limit (for that country)
        $hasReachedBuildingPerCountryLimit = $app['game.countries']
            ->hasReachedBuildingPerCountryLimit(
                $town->getCountry(),
                $building
            )
        ;
        if ($hasReachedBuildingPerCountryLimit) {
            throw new BuildingPerCountryLimitReachedException(
                'You have reached this buildings limit for this country!'
            );
        }

        /*** Per planet ***/
        // To-Do

        /*** Overall ***/
        // To-Do

        /***** Building - Requirement checks *****/
        /*** Required buildings ****/
        // Check if we have the required buildings to construct this building
        $hasRequiredBuildingsForBuilding = $app['game.towns']
            ->hasRequiredBuildingsForBuilding(
                $town,
                $building
            )
        ;
        if (!$hasRequiredBuildingsForBuilding) {
            throw new MissingRequiredBuildingsException(
                'You do not have the required buildings to construct this building!'
            );
        }

        /*** Required resources ***/
        // Check if that town has enough resources to build that building
        $hasEnoughResourcesForBuilding = $app['game.towns']
            ->hasEnoughResourcesForBuilding(
                $town,
                $building
            )
        ;
        if (!$hasEnoughResourcesForBuilding) {
            throw new InsufficientResourcesException(
                'You do not have enough resources to construct this building!'
            );
        }

        if (is_array($startingCoordinates)) {
            /*** Buildable on terrain ***/
            $isBuildableOnTerrain = $this
                ->isBuildableOnTerrain(
                    $planet,
                    $startingCoordinates,
                    $building
                )
            ;
            if (!$isBuildableOnTerrain) {
                throw new BuildingNotBuildableOnThisTerrain(
                    'This building not buildable on one or more of this tiles!'
                );
            }

            /*** Required area space ***/
            // Check if we have enough space to build this building
            $hasEnoughAreaSpace = $this->hasEnoughAreaSpace(
                $planet,
                $startingCoordinates,
                $building
            );
            if (!$hasEnoughAreaSpace) {
                throw new InsufficientAreaSpaceException(
                    'You do not have enough space to construct this building!'
                );
            }

            /*** Inside town radius ***/
            $isInsideRadius = $app['game.towns']
                ->isInsideRadius($town, $startingCoordinates)
            ;
            if (!$isInsideRadius) {
                throw new BuildingNotInsideTownRadius(
                    'This building is not inside the town radius!'
                );
            }
        }
    }

    /**
     * Same as the method above BUT a more frontend friendly version. Outputs text instead of exceptions.
     *
     *
     * @param PlanetEntity  $planet
     * @param TownEntity    $town
     * @param array|boolean $startingCoordinates The start coordinates (bottom left) of the location that building is going to be build. If it's not an array, then it won't do any coordinate related tests.
     * @param string        $building
     * @param string        $buildingStatus
     *
     * @return boolean|string
     */
    public function doPreBuildChecksResponse(
        PlanetEntity $planet,
        TownEntity $town,
        $startingCoordinates,
        $building
    ) {
        try {
            $this->doPreBuildChecks(
                $planet,
                $town,
                $startingCoordinates,
                $building
            );

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /******************** Upgrade ********************/
    /**
     * With this method we'll update the town building.
     *
     * @param TownBuildingEntity $townBuilding
     *
     * @return TownBuildingEntity
     */
    public function upgrade(TownBuildingEntity $townBuilding)
    {
        $app = $this->app;
        $town = $townBuilding->getTown();
        $buildingObject = $townBuilding->getBuildingObject();

        // Before the buy, update the town resources (storage) to the current state.
        $app['game.towns']->updateTownResources($town);

        /***** Checks *****/
        $this->doPreUpgradeChecks(
            $townBuilding
        );

        $buildTimeSeconds = $buildingObject->getBuildTime($townBuilding->getNextLevel());
        $timeNextLevelUpgradeStarted = new \Datetime();
        $timeNextLevelUpgradeEnds = new \Datetime();
        $timeNextLevelUpgradeEnds->add(new \DateInterval('PT'.$buildTimeSeconds.'S'));
        $townBuilding
            ->setTimeNextLevelUpgradeStarted($timeNextLevelUpgradeStarted)
            ->setTimeNextLevelUpgradeEnds($timeNextLevelUpgradeEnds)
        ;

        $app['orm.em']->persist($townBuilding);

        // Substract the resources in the town for that building
        $buildingResourcesCost = $buildingObject
            ->getResourcesCost($townBuilding->getNextLevel())
        ;
        $town->useResources($buildingResourcesCost);
        $app['orm.em']->persist($town);

        // Save enerything
        $app['orm.em']->flush();

        // Check if the user has earned any new badges
        $app['game']->badgesCheck();

        return $townBuilding;
    }

    /**
     * With this method we'll do checks if everyting fits before the building is being build.
     *
     * @param TownBuildingEntity $townBuilding
     *
     * @return void
     * @throws InsufficientResourcesException
     * @throws TownBuildingAlreadyUpgradingException
     * @throws TownBuildingNotUpgradableException
     */
    public function doPreUpgradeChecks(TownBuildingEntity $townBuilding)
    {
        $app = $this->app;

        // Check if the building is currently being constructed
        $isConstructing = $townBuilding->isConstructing();
        if ($isConstructing) {
            throw new TownBuildingInConstructionException(
                'This building is currently being constructed!'
            );
        }

        // Check if the building is currently being upgraded
        $isUpgrading = $townBuilding->isUpgrading();
        if ($isUpgrading) {
            throw new TownBuildingAlreadyUpgradingException(
                'The building is currently upgrading!'
            );
        }

        // Check if the building is upgradable
        $isAtMaximumLevel = $townBuilding->isAtMaximumLevel();
        if ($isAtMaximumLevel) {
            throw new TownBuildingAtMaximumLevelException(
                'This building is at maximum level!'
            );
        }

        // Check if we have the required buildings to upgrade this building
        $hasRequiredBuildingsForBuilding = $app['game.towns']
            ->hasRequiredBuildingsForBuilding(
                $townBuilding->getTown(),
                $townBuilding->getBuilding(),
                $townBuilding->getNextLevel()
            )
        ;
        if (!$hasRequiredBuildingsForBuilding) {
            throw new MissingRequiredBuildingsException(
                'You do not have the required buildings to upgrade this building!'
            );
        }

        // Check if that town has enough resources to build that building
        $hasEnoughResourcesForBuilding = $app['game.towns']
            ->hasEnoughResourcesForBuilding(
                $townBuilding->getTown(),
                $townBuilding->getBuilding(),
                $townBuilding->getNextLevel()
            )
        ;
        if (!$hasEnoughResourcesForBuilding) {
            throw new InsufficientResourcesException(
                'You do not have enough resources to upgrade this building!'
            );
        }
    }

    /**
     * Same as the method above BUT a more frontend friendly version. Outputs text instead of exceptions.
     *
     * @param TownBuildingEntity $townBuilding
     *
     * @return boolean|string
     */
    public function doPreUpgradeChecksResponse(TownBuildingEntity $townBuilding)
    {
        try {
            $this->doPreUpgradeChecks(
                $townBuilding
            );

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /******************** Checks ********************/
    /**
     * @return boolean
     */
    public function hasEnoughAreaSpace(
        PlanetEntity $planet,
        array $startingCoordinates = array(),
        $building
    ) {
        $requiredTiles = $this->getRequiredTiles($planet, $startingCoordinates, $building);
        if ($requiredTiles === false) {
            return false;
        }

        foreach ($requiredTiles as $requiredTile) {
            if ($requiredTile->isOccupied()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return boolean
     */
    public function isBuildableOnTerrain(
        PlanetEntity $planet,
        array $startingCoordinates = array(),
        $building
    ) {
        $requiredTiles = $this->getRequiredTiles($planet, $startingCoordinates, $building);
        if ($requiredTiles === false) {
            return false;
        }

        $buildingObject = $this->getAllWithData($building);

        foreach ($requiredTiles as $requiredTile) {
            $tileTerrainType = $requiredTile->getTerrainType();
            $buildingTerrainTypes = $buildingObject->getAvailableTerrainTypes();
            if (!in_array($tileTerrainType, $buildingTerrainTypes)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets all the required tiles for that building on that planet
     *
     * @param PlanetEntity $planet
     * @param array        $startingCoordinates
     * @param string       $building
     *
     * @return array|boolean If false is returned, that means, that not ALL required tiles could be fetched
     */
    public function getRequiredTiles(
        PlanetEntity $planet,
        array $startingCoordinates = array(),
        $building
    ) {
        $app = $this->app;
        $tiles = array();

        $coordinatesForBuilding = $this->getCoordinatesForBuilding(
            $startingCoordinates,
            $building
        );

        foreach ($coordinatesForBuilding as $coordinate) {
            $tileEntity = $app['orm.em']
                ->getRepository('Application\Entity\TileEntity')
                ->findOneBy(array(
                    'coordinatesX' => $coordinate['x'],
                    'coordinatesY' => $coordinate['y'],
                    'planet' => $planet,
                ))
            ;

            if (!$tileEntity) {
                return false;
            }

            $tiles[] = $tileEntity;
        }

        return $tiles;
    }

    /**
     * Gets all the coordinates (including building section coordinates) for that building.
     *
     * @param array  $startingCoordinates
     * @param string $building
     *
     * @return array
     */
    public function getCoordinatesForBuilding(
        array $startingCoordinates = array(),
        $building
    ) {
        $coordinates = array();

        $startX = $startingCoordinates[0];
        $startY = $startingCoordinates[1];
        $buildingObject = $this->getAllWithData($building);
        $size = $buildingObject->getSize();
        list($sizeX, $sizeY) = explode('x', $size);
        $x = $startX;
        $y = $startY;

        // Go thought the required tiles and set the current building to it
        foreach (range(1, (int) $sizeY) as $sizeYSingle) {
            $x = $startX;

            foreach (range(1, (int) $sizeX) as $sizeXSingle) {
                $coordinates[] = array(
                    'x' => $x,
                    'y' => $y,
                    'buildingSectionX' => $sizeXSingle,
                    'buildingSectionY' => $sizeYSingle,
                );

                $x++;
            }

            $y++;
        }

        return $coordinates;
    }

    /**
     * Gets the building section coordinates by the tile coordinates
     *
     * @param array  $coordinates
     * @param array  $startingCoordinates
     * @param string $building
     *
     * @return array|boolean
     */
    public function getBuildingSectionCoordinates(
        array $coordinates = array(),
        array $startingCoordinates = array(),
        $building
    ) {
        $x = $coordinates[0];
        $y = $coordinates[1];
        $coordinatesForBuilding = $this->getCoordinatesForBuilding(
            $startingCoordinates,
            $building
        );

        foreach ($coordinatesForBuilding as $coordinate) {
            if (
                $coordinate['x'] == $x &&
                $coordinate['y'] == $y
            ) {
                return array(
                    $coordinate['buildingSectionX'],
                    $coordinate['buildingSectionY'],
                );
            }
        }

        return false;
    }
}
