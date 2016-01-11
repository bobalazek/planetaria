<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class CountryRoles
{
    /**
     * @var string
     */
    const CREATOR = 'creator';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::CREATOR => 'Creator',
        );
    }
}
