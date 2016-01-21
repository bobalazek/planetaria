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
use Application\Game\Exception\BuildingPerTownLimitReachedException;
use Application\Game\Exception\TownBuildingAlreadyUpgradingException;
use Application\Game\Exception\TownBuildingNotUpgradableException;
use Application\Game\Exception\TownBuildingInConstructionException;
use Application\Game\Exception\MissingRequiredBuildingsException;

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
    const AIRBASE = 'airbase';

    /**
     * @var string
     */
    const BARRACKS = 'barracks';

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
    const ION_CANNON_CONTROL_CENTER = 'ion_cannon_control_center';

    /**
     * @var string
     */
    const MISSILE_LAUNCH_FACILITY = 'missile_launch_facility';

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
            self::BARRACKS => 'Barracks',
            self::AIRBASE => 'Airbase',
            self::ION_CANNON_CONTROL_CENTER => 'Ion cannon control center',
            self::MISSILE_LAUNCH_FACILITY => 'Missile launch facility',
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
        $buildings = self::getAll();

        if (!array_key_exists($key, $buildings)) {
            throw new \Exception('This building does not exists!');
        }

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

    /**
     * With this method we'll create the town building.
     *
     * @param PlanetEntity $planet
     * @param TownEntity   $town
     * @param array        $coordinates         The start coordinates (bottom left) of the location that building is going to be build
     * @param string       $building
     * @param string       $buildingStatus
     * @param boolean      $ignoreCapacityLimit Useful when creating a new town, so the town resources amount is NOT set to 0 (because at that point, you do not have any buildings, that gives you storage capacity)
     *
     * @return TownBuildingEntity
     */
    public function build(
        PlanetEntity $planet,
        TownEntity $town,
        array $startingCoordinates = array(),
        $building,
        $ignoreCapacityLimit = false
    ) {
        $app = $this->app;

        // Before the buy, update the town resources (storage) to the current state.
        $app['game.towns']->updateTownResources($town, $ignoreCapacityLimit);

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

        // Save everything
        $app['orm.em']->flush();

        return $townBuildingEntity;
    }

    /**
     * With this method we'll do checks if everyting fits before the building is being build.
     *
     * @param PlanetEntity $planet
     * @param TownEntity   $town
     * @param array        $coordinates    The start coordinates (bottom left) of the location that building is going to be build
     * @param string       $building
     * @param string       $buildingStatus
     *
     * @return void
     * @throws TownBuildingsLimitReachedException
     * @throws InsufficientResourcesException
     * @throws InsufficientAreaSpaceException
     */
    public function doPreBuildChecks(
        PlanetEntity $planet,
        TownEntity $town,
        array $startingCoordinates = array(),
        $building
    ) {
        $app = $this->app;

        // Check if we have reached the buildings limit
        $hasReachedBuildingsLimit = $app['game.towns']
            ->hasReachedBuildingLimit(
                $town,
                $building
            )
        ;
        if ($hasReachedBuildingsLimit) {
            throw new BuildingPerTownLimitReachedException(
                'You have reached the limit per town for this building!'
            );
        }

        // @To-Do: Check for limit per country!

        // Check if we have reached the buildings limit
        $hasReachedBuildingsLimit = $app['game.towns']
            ->hasReachedBuildingsLimit($town)
        ;
        if ($hasReachedBuildingsLimit) {
            throw new TownBuildingsLimitReachedException(
                'You have reached the buildings limit for this town!'
            );
        }

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
    }

    /**
     * Same as the method above BUT a more frontend friendly version. Outputs text instead of exceptions.
     *
     *
     * @param PlanetEntity $planet
     * @param TownEntity   $town
     * @param array        $coordinates    The start coordinates (bottom left) of the location that building is going to be build
     * @param string       $building
     * @param string       $buildingStatus
     *
     * @return boolean|string
     */
    public function doPreBuildChecksResponse(
        PlanetEntity $planet,
        TownEntity $town,
        array $startingCoordinates = array(),
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

        $app['orm.em']->flush();

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

        $isUpgrading = $townBuilding->isUpgrading();
        if ($isUpgrading) {
            throw new TownBuildingAlreadyUpgradingException(
                'The building is currently upgrading!'
            );
        }

        $isUpgradable = $townBuilding->isUpgradable();
        if (!$isUpgradable) {
            throw new TownBuildingNotUpgradableException(
                'This building is no more upgradable!'
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
            if (!$requiredTile->isCurrentlyBuildable()) {
                return false;
            }
        }

        return true;
    }

    /**
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
     * @return array
     */
    public function getCoordinatesForBuilding(array $startingCoordinates = array(), $building)
    {
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
     * @return array
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
