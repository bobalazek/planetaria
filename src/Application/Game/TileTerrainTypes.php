<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TileTerrainTypes
{
    /**
     * @var string
     */
    const GRASSLAND = 'grassland';

    /**
     * @var string
     */
    const PLAINS = 'plains';

    /**
     * @var string
     */
    const FOREST = 'forest';

    /**
     * @var string
     */
    const DESERT = 'desert';

    /**
     * @var string
     */
    const SWAMP = 'swamp';

    /**
     * @var string
     */
    const HILLS = 'hills';

    /**
     * @var string
     */
    const MOUNTAINS = 'mountains';

    /**
     * @var string
     */
    const OCEAN = 'ocean';

    /**
     * @var string
     */
    const GLACIERS = 'glaciers';

    /**
     * @var string
     */
    const TUNDRA = 'tundra';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::GRASSLAND => 'Grassland',
            self::PLAINS => 'Plains',
            self::FOREST => 'Forest',
            self::DESERT => 'Desert',
            self::SWAMP => 'Swamp',
            self::HILLS => 'Hills',
            self::MOUNTAINS => 'Mountains',
            self::OCEAN => 'Ocean',
            self::GLACIERS => 'Glaciers',
            self::TUNDRA => 'Tundra',
        );
    }

    /**
     * @param $terrain
     *
     * @return array
     */
    public static function getImages($terrain = null)
    {
        $images = array(
            self::GRASSLAND => array(
                '001.png',
                '002.png',
                '003.png',
                '004.png',
                '005.png',
                '006.png',
            ),
            self::PLAINS => array(),
            self::FOREST => array(
                '001.png',
                '002.png',
                '003.png',
                '004.png',
                '005.png',
                '006.png',
            ),
            self::DESERT => array(
                '001.png',
                '002.png',
                '003.png',
                '004.png',
                '005.png',
                '006.png',
                '007.png',
                '008.png',
                '009.png',
            ),
            self::SWAMP => array(),
            self::HILLS => array(),
            self::MOUNTAINS => array(
                '001.png',
                '002.png',
                '003.png',
                '004.png',
                '005.png',
                '006.png',
                '007.png',
                '008.png',
                '009.png',
            ),
            self::OCEAN => array(
                '001.png',
                '002.png',
                '003.png',
                '004.png',
                '005.png',
                '006.png',
                '007.png',
                '008.png',
                '009.png',
            ),
            self::GLACIERS => array(
                '001.png',
                '002.png',
                '003.png',
                '004.png',
                '005.png',
                '006.png',
            ),
            self::TUNDRA => array(),
        );

        return $terrain == null
            ? $images
            : $images[$terrain]
        ;
    }
}
