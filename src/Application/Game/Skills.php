<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class Skills
{
    /**
     * @var string
     */
    const STRENGTH = 'strength';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::STRENGTH => 'Strength',
        );
    }
}
