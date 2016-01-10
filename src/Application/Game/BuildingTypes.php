<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class BuildingTypes
{
    /**
     * @var string
     */
    const CIVIL = 'civil';

    /**
     * @var string
     */
    const MILITARY = 'military';

    /**
     * @var string
     */
    const OTHER = 'other';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::CIVIL => 'Civil',
            self::MILITARY => 'Military',
            self::OTHER => 'Other',
        );
    }
}
