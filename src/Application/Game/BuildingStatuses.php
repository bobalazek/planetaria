<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class BuildingStatuses
{
    /**
     * @var string
     */
    const CONSTRUCTING = 'constructing';

    /**
     * @var string
     */
    const OPERATIONAL = 'operational';

    /**
     * @var string
     */
    const UPGRADING = 'upgrading';

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
            self::OPERATIONAL => 'Operational',
            self::UPGRADING => 'Upgrading',
            self::DAMAGED => 'Damaged',
            self::DESTROYED => 'Destroyed',
        );
    }
}
