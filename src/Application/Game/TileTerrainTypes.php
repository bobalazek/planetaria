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
    const FOREST = 'forest';

    /**
     * @var string
     */
    const DESERT = 'desert';

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
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::GRASSLAND => 'Grassland',
            self::FOREST => 'Forest',
            self::DESERT => 'Desert',
            self::MOUNTAINS => 'Mountains',
            self::OCEAN => 'Ocean',
            self::GLACIERS => 'Glaciers',
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
        );

        return $terrain == null
            ? $images
            : $images[$terrain]
        ;
    }
}
