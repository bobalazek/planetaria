<?php

namespace Application\Game\Building;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
interface BuildingInterface
{
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
    public function getHealthPoints($level);

    /**
     * @param array $healthPoints
     */
    public function setHealthPoints(array $healthPoints);

    /***** Population capacity *****/
    /**
     * @return array|integer
     */
    public function getPopulationCapacity($level);

    /**
     * @param array $populationCapacity
     */
    public function setPopulationCapacity(array $populationCapacity);

    /***** Resources capacity *****/
    /**
     * @return array|integer
     */
    public function getResourcesCapacity($level);

    /**
     * @param array $resourcesCapacity
     */
    public function setResourcesCapacity(array $resourcesCapacity);

    /***** Build time *****/
    /**
     * @return array|integer
     */
    public function getBuildTime($level);

    /**
     * @param array $buildTime
     */
    public function setBuildTime(array $buildTime);

    /***** Resources cost *****/
    /**
     * @return array|integer
     */
    public function getResourcesCost($level, $resource);

    /**
     * @param array $resourcesCost
     */
    public function setResourcesCost(array $resourcesCost);

    /***** Resources production *****/
    /**
     * @return array
     */
    public function getResourcesProduction($level, $resource);

    /**
     * @param array $resourcesProduction
     */
    public function setResourcesProduction(array $resourcesProduction);

    /***** Resources use *****/
    /**
     * @return array
     */
    public function getResourcesUse($level, $resource);

    /**
     * @param array $resourcesUse
     */
    public function setResourcesUse(array $resourcesUse);

    /***** Units production *****/
    /**
     * @return array
     */
    public function getUnitsProduction($level, $unit);

    /**
     * @param array $unitsProduction
     */
    public function setUnitsProduction(array $unitsProduction);

    /***** Items production *****/
    /**
     * @return array
     */
    public function getItemsProduction($level, $item);

    /**
     * @param array $itemsProduction
     */
    public function setItemsProduction(array $itemsProduction);

    /***** Buildings required *****/
    /**
     * @return array
     */
    public function getBuildingsRequired($level, $building);

    /**
     * @param array $buildingsRequired
     */
    public function setBuildingsRequired(array $buildingsRequired);
}
