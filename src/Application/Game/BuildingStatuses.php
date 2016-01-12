<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class BuildingStatuses
{
    /**
     * @var string
     */
    const CONSTRUCTING = 'constructing';

    /**
     * @var string
     */
    const CONSTRUCTED = 'constructed';

    /**
     * @var string
     */
    const DAMAGED = 'damaged';

    /**
     * @var string
     */
    const DESTROYED = 'destroyed';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::CONSTRUCTING => 'Constructing',
            self::CONSTRUCTED => 'Constructed',
            self::DAMAGED => 'Damaged',
            self::DESTROYED => 'Destroyed',
        );
    }
}
