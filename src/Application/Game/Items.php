<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class Items
{
    /**
     * @var string
     */
    const SNIPER = 'sniper';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::SNIPER => 'Sniper',
        );
    }
}
