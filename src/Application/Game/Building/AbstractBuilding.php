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
     * How much experience points does the user earn if he builds / upgrades that building?
     * Example:
     *   array( 0 => 100, 1 => 200, 2 => 400 )
     * or
     *   100
     *
     * @var array|integer
     */
    protected $userExperiencePoints;

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
     * or
     *   100
     *
     * @var array|integer
     */
    protected $healthPoints;

    /**
     * What's the capacity this building gives (per level)?
     * Example:
     *   array( 0 => 10, 1 => 20, 2 => 30 )
     * or
     *   100
     *
     * @var array|integer
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
     * How much capacity does that building use?
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
    protected $resourcesUsage;

    /**
     * What does that building produce (per level)?
     * Example ( level => array( unit, unit2 ) ):
     *   array( 0 => array( 'soldier', 'rifleman' )), 1 => array( 'soldier', 'rifleman', 'rocketman' ) )
     *
     * @var array
     */
    protected $unitsProduction;

    /**
     * How much bonus for resources cost (in percents) do we get (per level)?
     * Example ( level => bonusPercents ):
     *   array( 0 => 0, 1 => 5, 2 => 10 )
     *
     * @var array
     */
    protected $unitsResourcesCostBonus;

    /**
     * How much bonus for build time (in percents) do we get (per level)?
     * Example ( level => bonusPercents ):
     *   array( 0 => 0, 1 => 5, 2 => 10 )
     *
     * @var array
     */
    protected $unitsBuildTimeBonus;

    /**
     * What does that building produce (per level)?
     * Example ( level => array( weapon, weapon2 ) ):
     *   array( 0 => array( 'ion_cannon_satelite' )), 1 => array( 'ion_cannon_satelite', 'nuclear_bomb' ) )
     *
     * @var array
     */
    protected $weaponsProduction;

    /**
     * How much bonus for resources cost (in percents) do we get (per level)?
     * Example ( level => bonusPercents ):
     *   array( 0 => 0, 1 => 5, 2 => 10 )
     *
     * @var array
     */
    protected $weaponsResourcesCostBonus;

    /**
     * How much bonus for build time (in percents) do we get (per level)?
     * Example ( level => bonusPercents ):
     *   array( 0 => 0, 1 => 5, 2 => 10 )
     *
     * @var array
     */
    protected $weaponsBuildTimeBonus;

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

    /**
     * What's the maximum number of this buildings on one planet?
     * Example:
     *   -1 (infinitive)
     *
     * @var integer
     */
    protected $perPlanetLimit = -1;

    /**
     * What's the maximum number of this buildings overall?
     * Example:
     *   -1 (infinitive)
     *
     * @var integer
     */
    protected $limit = -1;

    /**
     * On which terrain types can this building be build?
     * Example:
     *   array( 'grassland', 'plains', 'desert' )
     *
     * @var array
     */
    protected $availableTerrainTypes;

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

    /***** User experience points *****/
    /**
     * @return array|integer
     */
    public function getUserExperiencePoints($level = null)
    {
        $userExperiencePoints = $this->userExperiencePoints;

        if (is_array($userExperiencePoints)) {
            return $level === null
                ? $this->userExperiencePoints
                : $this->userExperiencePoints[$level]
            ;
        } elseif (is_numeric($userExperiencePoints)) {
            return $userExperiencePoints;
        }

        return 0;
    }

    /**
     * @param array|integer $userExperiencePoints
     */
    public function setUserExperiencePoints($userExperiencePoints)
    {
        $this->userExperiencePoints = $userExperiencePoints;

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

    /**
     * Used for the frontend, because we don't want to show zero levels!
     *
     * @return integer
     */
    public function getMaximumLevelDisplay()
    {
        return $this->getMaximumLevel() + 1;
    }

    /***** Health points *****/
    /**
     * @return array|integer
     */
    public function getHealthPoints($level = null)
    {
        $healthPoints = $this->healthPoints;

        if (is_array($healthPoints)) {
            return $level === null
                ? $this->healthPoints
                : $this->healthPoints[$level]
            ;
        } elseif (is_numeric($healthPoints)) {
            return $healthPoints;
        }

        return 100;
    }

    /**
     * @param array|integer $healthPoints
     */
    public function setHealthPoints($healthPoints)
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
        $populationCapacity = $this->populationCapacity;

        if (is_array($populationCapacity)) {
            return $level === null
                ? $this->populationCapacity
                : $this->populationCapacity[$level]
            ;
        } elseif (is_numeric($populationCapacity)) {
            return $populationCapacity;
        }

        return 10;
    }

    /**
     * @param array|integer $populationCapacity
     */
    public function setPopulationCapacity($populationCapacity)
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

    /***** Resources usage *****/
    /**
     * @return array
     */
    public function getResourcesUsage($level = null, $resource = null)
    {
        return $level === null
            ? $this->resourcesUsage
            : ($resource === null
                ? $this->resourcesUsage[$level]
                : $this->resourcesUsage[$level][$resource])
        ;
    }

    /**
     * @param array $resourcesUsage
     */
    public function setResourcesUsage(array $resourcesUsage = array())
    {
        $this->resourcesUsage = $resourcesUsage;

        return $this;
    }

    /***** Units production *****/
    /**
     * @return array
     */
    public function getUnitsProduction($level = null, $unit = null)
    {
        return $level === null
            ? $this->unitsProduction
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

    /***** Units resources cost bonus *****/
    /**
     * @return array|integer
     */
    public function getUnitsResourcesCostBonus($level = null)
    {
        return $level === null
            ? $this->unitsResourcesCostBonus
            : $this->unitsResourcesCostBonus[$level]
        ;
    }

    /**
     * @param array $unitsResourcesCostBonus
     */
    public function setUnitsResourcesCostBonus(array $unitsResourcesCostBonus = array())
    {
        $this->unitsResourcesCostBonus = $unitsResourcesCostBonus;

        return $this;
    }

    /***** Units build time bonus *****/
    /**
     * @return array|integer
     */
    public function getUnitsBuildTimeBonus($level = null)
    {
        return $level === null
            ? $this->unitsBuildTimeBonus
            : $this->unitsBuildTimeBonus[$level]
        ;
    }

    /**
     * @param array $unitsBuildTimeBonus
     */
    public function setUnitsBuildTimeBonus(array $unitsBuildTimeBonus = array())
    {
        $this->unitsBuildTimeBonus = $unitsBuildTimeBonus;

        return $this;
    }

    /***** Weapons production *****/
    /**
     * @return array
     */
    public function getWeaponsProduction($level = null, $weapon = null)
    {
        return $level === null
            ? $this->weaponsProduction
            : ($weapon === null
                ? $this->weaponsProduction[$level]
                : $this->weaponsProduction[$level][$weapon])
        ;
    }

    /**
     * @param array $weaponsProduction
     */
    public function setWeaponsProduction(array $weaponsProduction = array())
    {
        $this->weaponsProduction = $weaponsProduction;

        return $this;
    }

    /***** Weapons resources cost bonus *****/
    /**
     * @return array|integer
     */
    public function getWeaponsResourcesCostBonus($level = null)
    {
        return $level === null
            ? $this->weaponsResourcesCostBonus
            : $this->weaponsResourcesCostBonus[$level]
        ;
    }

    /**
     * @param array $weaponsResourcesCostBonus
     */
    public function setWeaponsResourcesCostBonus(array $weaponsResourcesCostBonus = array())
    {
        $this->weaponsResourcesCostBonus = $weaponsResourcesCostBonus;

        return $this;
    }

    /***** Weapons build time bonus *****/
    /**
     * @return array|integer
     */
    public function getWeaponsBuildTimeBonus($level = null)
    {
        return $level === null
            ? $this->weaponsBuildTimeBonus
            : $this->weaponsBuildTimeBonus[$level]
        ;
    }

    /**
     * @param array $weaponsBuildTimeBonus
     */
    public function setWeaponsBuildTimeBonus(array $weaponsBuildTimeBonus = array())
    {
        $this->weaponsBuildTimeBonus = $weaponsBuildTimeBonus;

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

    /***** Per planet limit *****/
    /**
     * @return integer
     */
    public function getPerPlanetLimit()
    {
        return $this->perPlanetLimit;
    }

    /**
     * @param integer $perPlanetLimit
     */
    public function setPerPlanetLimit($perPlanetLimit)
    {
        $this->perPlanetLimit = $perPlanetLimit;

        return $this;
    }

    /***** Limit *****/
    /**
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param integer $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /***** Available terrain types *****/
    /**
     * @return array
     */
    public function getAvailableTerrainTypes()
    {
        return $this->availableTerrainTypes;
    }

    /**
     * @param array $availableTerrainTypes
     */
    public function setAvailableTerrainTypes(array $availableTerrainTypes = array())
    {
        $this->availableTerrainTypes = $availableTerrainTypes;

        return $this;
    }

    /**
     * Returns the name of that building¸
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Returns data in array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'name' => $this->getName(),
            'key' => $this->getKey(),
            'slug' => $this->getSlug(),
            'description' => $this->getDescription(),
            'type' => $this->getType(),
            'size' => $this->getSize(),
            'size_x' => $this->getSizeX(),
            'size_y' => $this->getSizeY(),
            'maximum_level' => $this->getMaximumLevel(),
            'health_points' => $this->getHealthPoints(),
            'population_capacity' => $this->getPopulationCapacity(),
            'build_time' => $this->getBuildTime(),
            'resources_cost' => $this->getResourcesCost(),
            'resources_capacity' => $this->getResourcesCapacity(),
            'resources_production' => $this->getResourcesProduction(),
            'resources_usage' => $this->getResourcesUsage(),
            'units_production' => $this->getUnitsProduction(),
            'units_resources_cost_bonus' => $this->getUnitsResourcesCostBonus(),
            'units_build_time_bonus' => $this->getUnitsBuildTimeBonus(),
            'weapons_production' => $this->getWeaponsProduction(),
            'weapons_resources_cost_bonus' => $this->getWeaponsResourcesCostBonus(),
            'weapons_build_time_bonus' => $this->getWeaponsBuildTimeBonus(),
            'per_town_limit' => $this->getPerTownLimit(),
            'per_country_limit' => $this->getPerCountryLimit(),
            'per_planet_limit' => $this->getPerPlanetLimit(),
            'limit' => $this->getLimit(),
            'available_terrain_types' => $this->getAvailableTerrainTypes(),
        );
    }
}
