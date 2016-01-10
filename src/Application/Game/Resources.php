<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class Resources
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
     * @return array
     */
    public static function getAll()
    {
        return array(
            self::FOOD => 'Food',
            self::WOOD => 'Wood',
            self::COAL => 'Coal',
            self::ROCK => 'Rock',
            self::IRON_ORE => 'Iron ore',
            self::CRUDE_OIL => 'Crude oil',
        );
    }
}
