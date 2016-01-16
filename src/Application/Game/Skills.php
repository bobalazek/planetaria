<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Skills
{
    /**
     * @var string
     */
    const STRENGTH = 'strength';

    /**
     * @var string
     */
    const INTELLIGENCE = 'intelligence';

    /**
     * @var string
     */
    const LEADERSHIP = 'leadership';

    /**
     * @return array
     */
    public static function getAll($key = null)
    {
        $all = array(
            self::STRENGTH => 'Strength',
            self::INTELLIGENCE => 'Intelligence',
            self::LEADERSHIP => 'Leadership',
        );
        
        return $key === null
            ? $all
            : $all[$key]
        ;
    }
}
