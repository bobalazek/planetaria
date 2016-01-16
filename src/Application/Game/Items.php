<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Items
{
    /**
     * @var string
     */
    const ION_CANNON_SATELITE = 'ion_cannon_satelite';

    /**
     * @return array
     */
    public static function getAll($key = null)
    {
        $all = array(
            self::ION_CANNON_SATELITE => 'Ion cannon satelite',
        );

        return $key === null
            ? $all
            : $all[$key]
        ;
    }
}
