<?php

namespace Application\Game;

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
     * @var string
     */
    const OWNER = 'owner';

    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::CREATOR => 'Creator',
            self::OWNER => 'Owner',
        );
    }
}
