<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Resources
{
    /**
     * @var string
     */
    const FOOD = 'food';

    /**
     * @var string
     */
    const WOOD = 'wood';

    /**
     * @var string
     */
    const COAL = 'coal';

    /**
     * @var string
     */
    const ROCK = 'rock';

    /**
     * @var string
     */
    const IRON_ORE = 'iron_ore';

    /**
     * @var string
     */
    const CRUDE_OIL = 'crude_oil';
    
    /**
     * @var string
     */
    const ELECTRICITY = 'electricity';

    /**
     * @var string
     */
    const MONEY = 'money';

    /**
     * @return array
     */
    public static function getAll($key = null)
    {
        $all = array(
            self::FOOD => 'Food',
            self::WOOD => 'Wood',
            self::COAL => 'Coal',
            self::ROCK => 'Rock',
            self::IRON_ORE => 'Iron ore',
            self::CRUDE_OIL => 'Crude oil',
            self::ELECTRICITY => 'Electricity',
            self::MONEY => 'Money',
        );

        return $key === null
            ? $all
            : $all[$key]
        ;
    }

    /**
     * @return array
     */
    public static function getAllForTiles()
    {
        $all = self::getAll();

        unset($all[Resources::MONEY]); // We do not want money to be a "natural" resource

        return $all;
    }
}
