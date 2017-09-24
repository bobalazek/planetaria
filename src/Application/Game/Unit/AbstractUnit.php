<?php

namespace Application\Game\Building;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class AbstractUnit implements UnitInterface
{
    /**
     * What's the name of that building?
     * Example: 'Iron mine'
     *
     * @var string
     */
    protected $name;

    /**
     * What's the key of that building?
     * Example: 'iron_mine'
     *
     * @var string
     */
    protected $key;

    /**
     * What's the slug of that building?
     * Example: 'iron-ime'
     *
     * @var string
     */
    protected $slug;

    /**
     * What's the description of that building?
     * Example: 'A building which is used to produce iron.''
     *
     * @var string
     */
    protected $description;

    /**
     * What's the type of that building?
     * Example: 'civil'
     *
     * @var string
     */
    protected $type;

    /**
     * What's the maximum level of that building?
     * Example: 2
     *
     * @var integer
     */
    protected $maximumLevel;

    /**
     * How much population capacity will this unit use?
     *
     * @var integer
     */
    protected $capacity;

    /**
     * How much health points does that unit have (per level)?
     * Example: array( 0 => 400, 1 => 800, 2 => 1200 )
     *
     * @var array
     */
    protected $healthPoints;

    /**
     * How much attack points does that unit have (per level)?
     * Example: array( 0 => 400, 1 => 800, 2 => 1200 )
     *
     * @var array
     */
    protected $attackPoints;

    /**
     * How much defense points does that unit have (per level)?
     * Example: array( 0 => 400, 1 => 800, 2 => 1200 )
     *
     * @var array
     */
    protected $defensePoints;

    /**
     * How much speed does that unit have in tiles per minute (per level)?
     * Example: array( 0 => 1, 1 => 2, 2 => 4 )
     *
     * @var array
     */
    protected $speed;

    /**
     * What's the build time of that building in seconds (per level)?
     * Example: array( 0 => 30, 1 => 60, 2 => 120 )
     *
     * @var array
     */
    protected $buildTime;

    /**
     * How much of what does that building cost (per level)?
     * Example: array( 0 => array( 'wood' => 200 ), 1 => array( 'wood' => 200 ), 2 => array( 'wood' => 200 ) )
     *
     * @var array
     */
    protected $resourcesCost;

    /***** Name *****/
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /***** Key *****/
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /***** Description *****/
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /***** Type *****/
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /***** Maximum level *****/
    /**
     * @return integer
     */
    public function getMaximumLevel()
    {
        return $this->maximumLevel;
    }

    /**
     * @param integer $maximumLevel
     */
    public function setMaximumLevel($maximumLevel)
    {
        $this->maximumLevel = $maximumLevel;

        return $this;
    }

    /***** Capacity *****/
    /**
     * @return integer
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param integer $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /***** Health points *****/
    /**
     * @return array|integer
     */
    public function getHealthPoints($level = null)
    {
        return $level === null
            ? $this->healthPoints
            : $this->healthPoints[$level]
        ;
    }

    /**
     * @param array $healthPoints
     */
    public function setHealthPoints(array $healthPoints = array())
    {
        $this->healthPoints = $healthPoints;

        return $this;
    }

    /***** Build time *****/
    /**
     * @return array|integer
     */
    public function getBuildTime($level = null)
    {
        return $level === null
            ? $this->buildTime
            : $this->buildTime[$level]
        ;
    }

    /**
     * @param array $buildTime
     */
    public function setBuildTime(array $buildTime = array())
    {
        $this->buildTime = $buildTime;

        return $this;
    }

    /***** Resources cost *****/
    /**
     * @return array|integer
     */
    public function getResourcesCost($level = null, $resource = null)
    {
        return $level === null
            ? $this->resourcesCost
            : ($resource === null
                ? $this->resourcesCost[$level]
                : $this->resourcesCost[$level][$resource])
        ;
    }

    /**
     * @param array $resourcesCost
     */
    public function setResourcesCost(array $resourcesCost = array())
    {
        $this->resourcesCost = $resourcesCost;

        return $this;
    }
}
