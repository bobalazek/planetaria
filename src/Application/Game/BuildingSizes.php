<?php

namespace Application\Game;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class BuildingSizes
{
    /**
     * @return array
     */
    public static function getAll()
    {
        return array(
            '1x1' => '1x1',
            '2x2' => '2x2',
            '3x3' => '3x3',
            '4x4' => '4x4',
            '1x2' => '1x2',
            '1x3' => '1x3',
            '1x4' => '1x4',
            '2x4' => '2x4',
        );
    }
}
