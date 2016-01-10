<?php

namespace Application\Game\Building;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
interface BuildingInterface
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
     * What's the size of that building?
     * Example: '1x1'
     *
     * @var string
     */
    protected $size;
    
    /**
     * What's the maximum level of that building?
     * Example: 2
     *
     * @var integer
     */
    protected $maximumLevel;
    
    /**
     * How much health points does that building have (per level)?
     * Example: array( 0 => 400, 1 => 800, 2 => 1200 )
     *
     * @var array
     */
    protected $healthPoints;
    
    /**
     * What's the size of that building (per level)?
     * Example: array( 0 => 100, 1 => 200, 2 => 300 )
     *
     * @var array
     */
    protected $populationCapacity;
    
    /**
     * What's the storage capacity of that building (per level)?
     * Example: array( 0 => 50, 1 => 100, 2 => 200 )
     *
     * @var array
     */
    protected $storageCapacity;
    
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
    
    /**
     * How much of what does that building produce per minute (per level)?
     * Example: array( 0 => array( 'iron_ore' => 20 ), 1 => array( 'iron_ore' => 20 ), 2 => array( 'iron_ore' => 20 ) )
     *
     * @var array
     */
    protected $resourcesProduction;

    /***** Name *****/
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @param string $name
     */
    public function setName($name);
    
    /***** Key *****/
    /**
     * @return string
     */
    public function getKey();
    
    /**
     * @param string $key
     */
    public function setKey($name);

    /***** Slug *****/
    /**
     * @return string
     */
    public function getSlug();
    
    /**
     * @param string $slug
     */
    public function setSlug($slug);
    
    /***** Description *****/
    /**
     * @return string
     */
    public function getDescription();
    
    /**
     * @param string $description
     */
    public function setDescription($description);
    
    /***** Type *****/
    /**
     * @return string
     */
    public function getType();
    
    /**
     * @param string $type
     */
    public function setType($type);
    
    /***** Size *****/
    /**
     * @return string
     */
    public function getSize();
    
    /**
     * @param string $size
     */
    public function setSize($size);
    
    /***** Maximum level *****/
    /**
     * @return integer
     */
    public function getMaximumLevel();
    
    /**
     * @param integer $maximumLevel
     */
    public function setMaximumLevel($maximumLevel);
    
    /***** Health points *****/
    /**
     * @return array|integer
     */
    public function getHealthPoints();
    
    /**
     * @param array $healthPoints
     */
    public function setHealthPoints($healthPoints);
    
    /***** Population capacity *****/
    /**
     * @return array|integer
     */
    public function getPopulationCapacity();
    
    /**
     * @param array $populationCapacity
     */
    public function setPopulationCapacity($populationCapacity);
    
    /***** Storage capacity *****/
    /**
     * @return array|integer
     */
    public function getStorageCapacity();
    
    /**
     * @param array $storageCapacity
     */
    public function setStorageCapacity($storageCapacity);
    
    /***** Build time *****/
    /**
     * @return array|integer
     */
    public function getBuildTime();
    
    /**
     * @param array $buildTime
     */
    public function setBuildTime($buildTime);
    
    /***** Resources cost *****/
    /**
     * @return array|integer
     */
    public function getResourcesCost();
    
    /**
     * @param array $resourcesCost
     */
    public function setResourcesCost($resourcesCost);
    
    /***** Resources production *****/
    /**
     * @return array
     */
    public function getResourcesProduction();
    
    /**
     * @param array $resourcesProduction
     */
    public function setResourcesProduction($resourcesProduction);
}
