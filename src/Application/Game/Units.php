<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class Units
{
    /**
     * @var string
     */
    const TANK = 'tank';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::TANK => 'Tank',
        );
    }
}
