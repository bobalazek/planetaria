<?php

namespace Application\Game;

use Silex\Application;
use Doctrine\Common\Util\Inflector;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Badges
{
    /**
     * @var string
     */
    const BEGINNER = 'beginner';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return array
     */
    public static function getAll($key = null)
    {
        $all = array(
            self::BEGINNER => 'Beginner',
        );

        return $key === null
            ? $all
            : $all[$key]
        ;
    }

    /**
     * @return string
     */
    public static function getClassName($key)
    {
        return Inflector::classify($key);
    }

    /**
     * @return array
     */
    public static function getAllWithData($key = null)
    {
        $badges = self::getAll();

        foreach ($badges as $badge => $badgeName) {
            $className = 'Application\\Game\\Badge\\'.self::getClassName($badge);
            $badgeObject = new $className();

            if (
                $key !== null &&
                $key === $badge
            ) {
                return $badgeObject;
            }

            $badges[$badge] = $badgeObject;
        }

        return $badges;
    }
}
