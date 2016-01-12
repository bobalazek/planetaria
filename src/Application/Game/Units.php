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
    const SOLDIER = 'soldier';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::SOLDIER => 'Soldier',
        );
    }
}
