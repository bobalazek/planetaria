<?php

namespace Application\Game;

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
     * @return array
     */
    public static function getAllByClassName($key = null)
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

        return $key === null
            ? $all
            : $all[$key]
        ;
    }
}
