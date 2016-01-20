<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class BuildingTypes
{
    /**
     * @var string
     */
    const GOVERNMENT = 'government';
    
    /**
     * @var string
     */
    const RESIDENTIAL = 'residential';
    
    /**
     * @var string
     */
    const AGRICULTURAL = 'agricultural';

    /**
     * @var string
     */
    const COMMERCIAL = 'commercial';
    
    /**
     * @var string
     */
    const MILITARY = 'military';
    
    /**
     * @var string
     */
    const INDUSTRIAL = 'industrial';

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
            self::GOVERNMENT => 'Government',
            self::RESIDENTIAL => 'Residential',
            self::AGRICULTURAL => 'Agricultural',
            self::COMMERCIAL => 'Commercial',
            self::MILITARY => 'Military',
            self::INDUSTRIAL => 'Industrial',
            self::OTHER => 'Other',
        );
    }
}
