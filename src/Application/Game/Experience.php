<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
final class Experience
{
    /**
     * @return array
     */
    public static function getLevels()
    {
        // level => minimumPoints
        return array(
            0 => 0,
            1 => 100,
            2 => 200,
            3 => 500,
            4 => 1000,
            5 => 2000,
            6 => 5000,
            7 => 10000,
            8 => 20000,
            9 => 50000,
            10 => 100000,
        );
    }

    /**
     * @return integer
     */
    public static function getLevelByPoints($points)
    {
        $level = 0;
        $levels = self::getLevels();

        foreach ($levels as $singleLevel => $minimumPoints) {
            if ($points > $minimumPoints) {
                $level = $singleLevel;
            }
        }

        return $level;
    }

    /**
     * @return integer
     */
    public static function getPointsByLevel($level)
    {
        $levels = self::getLevels();

        if (isset($levels[$level])) {
            return $levels[$level];
        }

        return 0;
    }
}
