<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UnitTypes
{
    /**
     * @var string
     */
    const INFANTRY = 'infantry';

    /**
     * @var string
     */
    const VEHICLE = 'vehicle';

    /**
     * @var string
     */
    const AIRCRAFT = 'aircraft';
    
    /**
     * @var string
     */
    const NAVAL = 'naval';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::INFANTRY => 'Infantry',
            self::VEHICLE => 'Vehicle',
            self::AIRCRAFT => 'Aircraft',
            self::NAVAL => 'Naval',
        );
    }
}
