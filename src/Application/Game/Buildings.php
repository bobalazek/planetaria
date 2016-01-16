<?php

namespace Application\Game;

use Silex\Application;
use Doctrine\Common\Util\Inflector;
use Application\Entity\TownEntity;
use Application\Entity\PlanetEntity;
use Application\Entity\TownBuildingEntity;
use Application\Game\Exception\TileNotBuildableException;
use Application\Game\Exception\TileNotExistsException;
use Application\Game\Exception\InsufficientResourcesException;
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

        $startX = isset($startingCoordinates[0])
            ? $startingCoordinates[0]
            : 0
        ;
        $startY = isset($startingCoordinates[1])
            ? $startingCoordinates[1]
            : 0
        ;

        $buildingClassName = 'Application\\Game\\Building\\'.$this->getClassName($building);
        $buildingObject = new $buildingClassName();

        $size = $buildingObject->getSize();
        list($sizeX, $sizeY) = explode('x', $size);
        $x = $startX;
        $y = $startY;
        
        $townBuildingsCount = count($town->getTownBuildings());
        $townBuildingsLimit = $town->getBuildingsLimit();
        
        if ($townBuildingsCount >= $townBuildingsLimit) {
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
        
        $buildTimeSeconds = $buildingObject->getBuildTime(0);
        $timeConstructed = new \Datetime();
        $timeConstructed->add(new \DateInterval('PT'.$buildTimeSeconds.'S'));
        $townBuildingEntity = new TownBuildingEntity();
        $townBuildingEntity
            ->setBuilding($building)
            ->setTown($town)
            ->setTimeConstructed($timeConstructed)
        ;
        $app['orm.em']->persist($townBuildingEntity);

        foreach (range(1, (int) $sizeY) as $sizeYSingle) {
            $x = $startX;
            foreach (range(1, (int) $sizeX) as $sizeXSingle) {
                // Tiles
                $tileEntity = $app['orm.em']
                    ->getRepository('Application\Entity\TileEntity')
                    ->findOneBy(array(
                        'coordinatesX' => $x,
                        'coordinatesY' => $y,
                        'planet' => $planet,
                    ))
                ;
                
                if (!$tileEntity) {
                    throw new TileNotExistsException(
                        'This tile ('.$x.','.$y.') does not exists!'
                    );
                }

                if (!$tileEntity->isBuildableCurrently()) {
                    throw new TileNotBuildableException(
                        'This building has not enough space to be constructed (building size: '.$size.').'
                    );
                }

                $tileEntity
                    ->setTownBuilding($townBuildingEntity)
                    ->setBuildingSection($sizeXSingle.'x'.$sizeYSingle)
                ;
                $app['orm.em']->persist($tileEntity);

                $x++;
            }

            $y++;
        }
        
        // Substract the resources in the town for the building
        $buildingResourcesCost = $buildingObject->getResourcesCost(0);
        $town->useResources($buildingResourcesCost);
        $app['orm.em']->persist($town);

        $app['orm.em']->flush();

        return $townBuildingEntity;
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
