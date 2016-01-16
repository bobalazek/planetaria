<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TileStatuses
{
    /**
     * @var string
     */
    const ORIGINAL = 'original';

    /**
     * @var string
     */
    const DAMAGED = 'damaged';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::ORIGINAL => 'Original',
            self::DAMAGED => 'Damaged',
        );
    }
}
