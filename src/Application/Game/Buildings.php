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
     * @to-do: Throw exception if it's not buildable (is overlapping an extisting building on a tile).
     *
     * @param PlanetEntity $planet
     * @param TownEntity   $town
     * @param array        $coordinates    The start coordinates (bottom left) of the location that building is going to be build
     * @param string       $building
     * @param string       $buildingStatus
     *
     * @return TownBuildingEntity
     */
    public function build(PlanetEntity $planet, TownEntity $town, array $startingCoordinates = array(), $building, $buildingStatus)
    {
        $app = $this->app;

        // @to-do: Check is startingCoordinates is a array with 2 values (x, y)
        $startX = $startingCoordinates[0];
        $startY = $startingCoordinates[1];

        $townBuildingEntity = new TownBuildingEntity();

        $townBuildingEntity
            ->setBuilding($building)
            ->setStatus($buildingStatus)
            ->setTown($town)
        ;

        $app['orm.em']->persist($townBuildingEntity);

        $buildingClassName = 'Application\\Game\\Building\\'.$this->getClassName($building);
        $buildingClass = new $buildingClassName();

        $size = $buildingClass->getSize();
        list($sizeX, $sizeY) = explode('x', $size);
        $x = $startX;
        $y = $startY;

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

                if (!$tileEntity->isBuildableCurrently()) {
                    throw new \Exception(
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
    public static function getAllWithData()
    {
        $buildings = self::getAll();

        foreach ($buildings as $building => $buildingName) {
            $className = 'Application\\Game\\Building\\'.self::getClassName($building);
            $buildingObject = new $className();
            $buildings[$building] = $buildingObject;
        }

        return $buildings;
    }
}
