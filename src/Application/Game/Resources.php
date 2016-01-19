<?php

namespace Application\Game;

use Silex\Application;
use Doctrine\Common\Util\Inflector;

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
     * @return string
     */
    public static function getClassName($key)
    {
        $buildings = self::getAll();

        if (!array_key_exists($key, $buildings)) {
            throw new \Exception('This resource does not exists!');
        }

        return Inflector::classify($key);
    }

    /**
     * @return array
     */
    public static function getAllForTiles()
    {
        $all = self::getAll();

        // Remove the non-natural resources
        unset($all[Resources::ELECTRICITY]);
        unset($all[Resources::MONEY]);

        return $all;
    }
    
    /**
     * @return array
     */
    public static function getAllWithData($key = null)
    {
        $resources = self::getAll();

        foreach ($resources as $resource => $resourceName) {
            $className = 'Application\\Game\\Resource\\'.self::getClassName($resource);
            $resourceObject = new $className();

            if (
                $key !== null &&
                $key === $resource
            ) {
                return $resourceObject;
            }

            $resources[$resource] = $resourceObject;
        }

        return $resources;
    }
}
