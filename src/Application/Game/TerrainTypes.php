<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class TerrainTypes
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
    const JUNGLE = 'jungle';
    
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
    const GLACIER = 'glacier';
    
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
            self::JUNGLE => 'Jungle',
            self::DESERT => 'Desert',
            self::SWAMP => 'Swamp',
            self::HILLS => 'Hills',
            self::MOUNTAINS => 'Mountains',
            self::OCEAN => 'Ocean',
            self::GLACIER => 'Glacier',
            self::TUNDRA => 'Tundra',
        );
    }
}
