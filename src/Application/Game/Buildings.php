<?php

namespace Application\Game;

use Silex\Application;
use Application\Entity\TownEntity;
use Application\Entity\TownBuildingEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class Buildings
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
    const BARRACS = 'barracs';

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
     * @param TownEntity $town
     * @param string $building
     * @param array $coordinates The start coordinates (bottom left) of the location that building is going to be build
     */
    public function build(TownEntity $town, $building, array $startingCoordinates = array())
    {
        $app = $this->app;

        $startX = $startingCoordinates[0];
        $startY = $startingCoordinates[1];
        
        $townBuildingEntity = new TownBuildingEntity();
        
        $townBuildingEntity
            ->setBuilding($building)
            ->setStatus(BuildingStatuses::CONSTRUCTED)
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
                    ->findOneBy(array( 'coordinatesX' => $x, 'coordinatesY' => $y ))
                ;
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
    }

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::CAPITOL => 'Capitol',
            self::HOUSE => 'House',
            self::SKYSCRAPER => 'Skyscraper',
            self::WAREHOUSE => 'Warehouse',
            self::FARM => 'Farm',
            self::MARKET => 'Market',
            self::AIRBASE => 'Airbase',
            self::BARRACS => 'Barracks',
            self::PUMPJACK => 'Pumpjack',
            self::QUARRY => 'Quarry',
            self::LOGGING_CAMP => 'Logging camp',
            self::COLLIERY => 'Colliery',
            self::IRON_MINE => 'Iron mine',
            self::DOCK => 'Dock',
        );
    }

    /**
     * @return string
     */
    public static function getClassName($key)
    {
        $all = array(
            self::CAPITOL => 'Capitol',
            self::HOUSE => 'House',
            self::SKYSCRAPER => 'Skyscraper',
            self::WAREHOUSE => 'Warehouse',
            self::FARM => 'Farm',
            self::MARKET => 'Market',
            self::AIRBASE => 'Airbase',
            self::BARRACS => 'Barracks',
            self::PUMPJACK => 'Pumpjack',
            self::QUARRY => 'Quarry',
            self::LOGGING_CAMP => 'LoggingCamp',
            self::COLLIERY => 'Colliery',
            self::IRON_MINE => 'IronMine',
            self::DOCK => 'Dock',
        );

        return $all[$key];
    }
}
