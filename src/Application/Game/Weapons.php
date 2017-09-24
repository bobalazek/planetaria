<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Weapons
{
    /**
     * @var string
     */
    const ION_CANNON_SATELITE = 'ion_cannon_satelite';

    /**
     * @var string
     */
    const NUCLEAR_BOMB = 'nuclear_bomb';

    /**
     * @return array
     */
    public static function getAll($key = null)
    {
        $all = array(
            self::ION_CANNON_SATELITE => 'Ion cannon satelite',
            self::NUCLEAR_BOMB => 'Nuclear bomb',
        );

        return $key === null
            ? $all
            : $all[$key]
        ;
    }
}
