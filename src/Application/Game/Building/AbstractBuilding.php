<?php

namespace Application\Game\Building;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class AbstractBuilding implements BuildingInterface
{
    /**
     * What's the name of that building?
     * Example:
     *   'Iron mine'
     *
     * @var string
     */
    protected $name;

    /**
     * What's the key of that building?
     * Example:
     *   'iron_mine'
     *
     * @var string
     */
    protected $key;

    /**
     * What's the slug of that building?
     * Example:
     *   'iron-mine'
     *
     * @var string
     */
    protected $slug;

    /**
     * What's the description of that building?
     * Example:
     *   'A building which is used to produce iron.'
     *
     * @var string
     */
    protected $description;

    /**
     * What's the type of that building?
     * Example:
     *   'civil'
     *
     * @var string
     */
    protected $type;

    /**
     * What's the size of that building?
     * Example:
     *   '1x1'
     *
     * @var string
     */
    protected $size = '1x1';

    /**
     * What's the maximum level of that building?
     * Example:
     *   2
     *
     * @var integer
     */
    protected $maximumLevel = 0;

    /**
     * How much health points does that building have (per level)?
     * Example:
     *   array( 0 => 400, 1 => 800, 2 => 1200 )
     *
     * @var array
     */
    protected $healthPoints;

    /**
     * What's the capacity this building gives (per level)?
     * Example:
     *   array( 0 => 100, 1 => 200, 2 => 300 )
     *
     * @var array
     */
    protected $populationCapacity;

    /**
     * What's the storage capacity of that building (per level)?
     * Example (increases capacity for ALL resources):
     *   array( 0 => 50, 1 => 100, 2 => 200 )
     * or if you only want to add storage for certain resources
     *   array( 0 => array( 'rock' => 200 ), 1 => array( 'rock' => 400 ), 2 => array( 'rock' => 800 ) )
     *
     * @var array
     */
    protected $resourcesCapacity;

    /**
     * How much capacity does that building use (town buildings limit)?
     * Example:
     *   1
     *
     * @var array
     */
    protected $buildingsCapacity = 1;

    /**
     * What's the build time of that building in seconds (per level)?
     * Example:
     *   array( 0 => 30, 1 => 60, 2 => 120 )
     *
     * @var array
     */
    protected $buildTime;

    /**
     * How much of what does that building cost (per level)?
     * Example:
     *   array( 0 => array( 'wood' => 200 ), 1 => array( 'wood' => 200 ), 2 => array( 'wood' => 200 ) )
     *
     * @var array
     */
    protected $resourcesCost;

    /**
     * How much of what does that building produce per minute (per level)?
     * Example ( level => array( resource => amountPerMinute ) ):
     *   array( 0 => array( 'iron_ore' => 20 ), 1 => array( 'iron_ore' => 20 ), 2 => array( 'iron_ore' => 20 ) )
     *
     * @var array
     */
    protected $resourcesProduction;

    /**
     * How much of what does that building use per minute (per level)?
     * Example ( level => array( resource => amountPerMinute ) ):
     *   array( 0 => array( 'iron_ore' => 1 ), 1 => array( 'iron_ore' => 1 ), 2 => array( 'iron_ore' => 1 ) )
     *
     * @var array
     */
    protected $resourcesUse;

    /**
     * How much of what does that building produce per minute (per level)?
     * Example ( level => array( unit => buildingTimeInSeconds ) ):
     *   array( 0 => array( 'soldier' => 600 )), 1 => array( 'soldier' => 300 ) )
     *
     * @var array
     */
    protected $unitsProduction;

    /**
     * How much of what does that building produce per minute (per level)?
     * Example ( level => array( unit => buildingTimeInSeconds ) ):
     *   array( 0 => array( 'ion_cannon_satelite' => 3600 )), 1 => array( 'ion_cannon_satelite' => 3000 ) )
     *
     * @var array
     */
    protected $itemsProduction;

    /**
     * Which buildings do we need before we can build this one?
     * Example ( level => array( building => minimumLevel ) ):
     *   array( 0 => array( 'farm' => 0 )) )
     *
     * @var array
     */
    protected $buildingsRequired;

    /**
     * What's the maximum number of this buildings in one town?
     * Example:
     *   -1 (infinitive)
     *
     * @var integer
     */
    protected $perTownLimit = -1;

    /**
     * What's the maximum number of this buildings in one country?
     * Example:
     *   -1 (infinitive)
     *
     * @var integer
     */
    protected $perCountryLimit = -1;

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

    /***** Slug *****/
    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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

    /***** Size *****/
    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSizeX()
    {
        $sizeExploded = explode('x', $this->getSize());

        return $sizeExploded[0];
    }

    /**
     * @return integer
     */
    public function getSizeY()
    {
        $sizeExploded = explode('x', $this->getSize());

        return $sizeExploded[1];
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

    /***** Population capacity *****/
    /**
     * @return array|integer
     */
    public function getPopulationCapacity($level = null)
    {
        return $level === null
            ? $this->populationCapacity
            : $this->populationCapacity[$level]
        ;
    }

    /**
     * @param array $populationCapacity
     */
    public function setPopulationCapacity(array $populationCapacity = array())
    {
        $this->populationCapacity = $populationCapacity;

        return $this;
    }

    /***** Resources capacity *****/
    /**
     * @return array|integer
     */
    public function getResourcesCapacity($level = null)
    {
        return $level === null
            ? $this->resourcesCapacity
            : $this->resourcesCapacity[$level]
        ;
    }

    /**
     * @param array $resourcesCapacity
     */
    public function setResourcesCapacity(array $resourcesCapacity = array())
    {
        $this->resourcesCapacity = $resourcesCapacity;

        return $this;
    }

    /***** Buildings capacity *****/
    /**
     * @return integer
     */
    public function getBuildingsCapacity()
    {
        return $this->buildingsCapacity;
    }

    /**
     * @param integer $buildingsCapacity
     */
    public function setBuildingsCapacity($buildingsCapacity)
    {
        $this->buildingsCapacity = $buildingsCapacity;

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

    /***** Resources production *****/
    /**
     * @return array
     */
    public function getResourcesProduction($level = null, $resource = null)
    {
        return $level === null
            ? $this->resourcesProduction
            : ($resource === null
                ? $this->resourcesProduction[$level]
                : $this->resourcesProduction[$level][$resource])
        ;
    }

    /**
     * @param array $resourcesProduction
     */
    public function setResourcesProduction(array $resourcesProduction = array())
    {
        $this->resourcesProduction = $resourcesProduction;

        return $this;
    }

    /***** Resources use *****/
    /**
     * @return array
     */
    public function getResourcesUse($level = null, $resource = null)
    {
        return $level === null
            ? $this->resourcesUse
            : ($resource === null
                ? $this->resourcesUse[$level]
                : $this->resourcesUse[$level][$resource])
        ;
    }

    /**
     * @param array $resourcesUse
     */
    public function setResourcesUse(array $resourcesUse = array())
    {
        $this->resourcesUse = $resourcesUse;

        return $this;
    }

    /***** Units production *****/
    /**
     * @return array
     */
    public function getUnitsProduction($level = null, $unit = null)
    {
        return $level === null
            ? $this->unitProductions
            : ($unit === null
                ? $this->unitsProduction[$level]
                : $this->unitsProduction[$level][$unit])
        ;
    }

    /**
     * @param array $unitsProduction
     */
    public function setUnitsProduction(array $unitsProduction = array())
    {
        $this->unitsProduction = $unitsProduction;

        return $this;
    }

    /***** Items production *****/
    /**
     * @return array
     */
    public function getItemsProduction($level = null, $item = null)
    {
        return $level === null
            ? $this->itemsProduction
            : ($item === null
                ? $this->itemsProduction[$level]
                : $this->itemsProduction[$level][$item])
        ;
    }

    /**
     * @param array $itemsProduction
     */
    public function setItemsProduction(array $itemsProduction = array())
    {
        $this->itemsProduction = $itemsProduction;

        return $this;
    }

    /***** Buildings required *****/
    /**
     * @return array
     */
    public function getBuildingsRequired($level = null, $building = null)
    {
        return $level === null
            ? $this->buildingsRequired
            : ($building === null
                ? $this->buildingsRequired[$level]
                : $this->buildingsRequired[$level][$building])
        ;
    }

    /**
     * @param array $buildingsRequired
     */
    public function setBuildingsRequired(array $buildingsRequired = array())
    {
        $this->buildingsRequired = $buildingsRequired;

        return $this;
    }

    /***** Per town limit *****/
    /**
     * @return integer
     */
    public function getPerTownLimit()
    {
        return $this->perTownLimit;
    }

    /**
     * @param integer $perTownLimit
     */
    public function setPerTownLimit($perTownLimit)
    {
        $this->perTownLimit = $perTownLimit;

        return $this;
    }

    /***** Per country limit *****/
    /**
     * @return integer
     */
    public function getPerCountryLimit()
    {
        return $this->perCountryLimit;
    }

    /**
     * @param integer $perCountryLimit
     */
    public function setPerCountryLimit($perCountryLimit)
    {
        $this->perCountryLimit = $perCountryLimit;

        return $this;
    }
}
