<?php

namespace Application\Game;

use Silex\Application;
use Doctrine\Common\Util\Inflector;
use Application\Entity\TownEntity;
use Application\Entity\PlanetEntity;
use Application\Entity\TownBuildingEntity;
use Application\Game\Exception\TileNotBuildableException;
use Application\Game\Exception\InsufficientResourcesException;
use Application\Game\Exception\InsufficientAreaSpaceException;
use Application\Game\Exception\TownBuildingsLimitReachedException;

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
    const SKYSCRAPER = 'skyscraper';

    /**
     * @var string
     */
    const WAREHOUSE = 'warehouse';

    /**
     * @var string
     */
    const FARM = 'farm';

    /**
     * @var string
     */
    const MARKET = 'market';

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
    const DOCK = 'dock';

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
     * With this method we'll create the town building.
     *
     * @param PlanetEntity $planet
     * @param TownEntity   $town
     * @param array        $coordinates    The start coordinates (bottom left) of the location that building is going to be build
     * @param string       $building
     * @param string       $buildingStatus
     *
     * @return TownBuildingEntity
     */
    public function build(PlanetEntity $planet, TownEntity $town, array $startingCoordinates = array(), $building)
    {
        $app = $this->app;

        // Before the buy, update the town resources (storage) to the current state.
        $app['game.towns']->updateTownResources($town);
        
        /***** Checks *****/
        // Check if we have reached the buildings limit
        $hasReachedBuildingsLimit = $app['game.towns']
            ->hasReachedBuildingsLimit($town)
        ;
        if ($hasReachedBuildingsLimit) {
            throw new TownBuildingsLimitReachedException(
                'You have reached the buildings limit for this town!'
            );
        }

        // Check if that town has enough resources to build that building
        $hasEnoughResourcesForBuilding = $app['game.towns']
            ->hasEnoughResourcesForBuilding($town, $building)
        ;
        if (!$hasEnoughResourcesForBuilding) {
            throw new InsufficientResourcesException(
                'You do not have enough resources to construct this building!'
            );
        }
        
        // Check if we have enough space to build this building
        $hasEnoughAreaSpace = $this->hasEnoughAreaSpace($planet, $startingCoordinates, $building);
        if (!$hasEnoughAreaSpace) {
            throw new InsufficientAreaSpaceException(
                'You do not have enough space to construct this building!'
            );
        }
        
        // Save the town building
        $buildingObject = $this->getAllWithData($building);
        $buildTimeSeconds = $buildingObject->getBuildTime(0); // How long does the initial level take to build?
        $timeConstructed = new \Datetime();
        $timeConstructed->add(new \DateInterval('PT'.$buildTimeSeconds.'S'));
        $townBuildingEntity = new TownBuildingEntity();
        $townBuildingEntity
            ->setBuilding($building)
            ->setTown($town)
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
     * @return boolean
     */
    public function hasEnoughAreaSpace(PlanetEntity $planet, array $startingCoordinates = array(), $building)
    {
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
    public function getRequiredTiles(PlanetEntity $planet, array $startingCoordinates = array(), $building)
    {
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
     * @return array|boolean
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
    public function getBuildingSectionCoordinates(array $coordinates = array(), array $startingCoordinates = array(), $building)
    {
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

    /**
     * @return array
     */
    public static function getAll($key = null)
    {
        $all = array(
            self::CAPITOL => 'Capitol',
            self::HOUSE => 'House',
            self::SKYSCRAPER => 'Skyscraper',
            self::WAREHOUSE => 'Warehouse',
            self::FARM => 'Farm',
            self::MARKET => 'Market',
            self::AIRBASE => 'Airbase',
            self::BARRACKS => 'Barracks',
            self::PUMPJACK => 'Pumpjack',
            self::QUARRY => 'Quarry',
            self::LOGGING_CAMP => 'Logging camp',
            self::COLLIERY => 'Colliery',
            self::IRON_MINE => 'Iron mine',
            self::DOCK => 'Dock',
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
}
