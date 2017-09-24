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

    /***** Description *****/
    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     */
    public function setDescription($description);

    /***** User experience points *****/
    /**
     * @return integer
     */
    public function getUserExperiencePoints($level);

    /**
     * @param integer $userExperiencePoints
     */
    public function setUserExperiencePoints($userExperiencePoints);

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
    public function setHealthPoints($healthPoints);

    /***** Population capacity *****/
    /**
     * @return array|integer
     */
    public function getPopulationCapacity($level);

    /**
     * @param array $populationCapacity
     */
    public function setPopulationCapacity($populationCapacity);

    /***** Resources capacity *****/
    /**
     * @return array|integer
     */
    public function getResourcesCapacity($level);

    /**
     * @param array $resourcesCapacity
     */
    public function setResourcesCapacity(array $resourcesCapacity);

    /***** Buildings capacity *****/
    /**
     * @return array|integer
     */
    public function getBuildingsCapacity();

    /**
     * @param array $buildingsCapacity
     */
    public function setBuildingsCapacity($buildingsCapacity);

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

    /***** Resources usage *****/
    /**
     * @return array
     */
    public function getResourcesUsage($level, $resource);

    /**
     * @param array $resourcesUsage
     */
    public function setResourcesUsage(array $resourcesUsage);

    /***** Units production *****/
    /**
     * @return array
     */
    public function getUnitsProduction($level, $unit);

    /**
     * @param array $unitsProduction
     */
    public function setUnitsProduction(array $unitsProduction);

    /***** Units resources cost bonus *****/
    /**
     * @return array
     */
    public function getUnitsResourcesCostBonus($level);

    /**
     * @param array $unitsResourcesCostBonus
     */
    public function setUnitsResourcesCostBonus(array $unitsResourcesCostBonus);

    /***** Units build time bonus *****/
    /**
     * @return array
     */
    public function getUnitsBuildTimeBonus($level);

    /**
     * @param array $unitsBuildTimeBonus
     */
    public function setUnitsBuildTimeBonus(array $unitsBuildTimeBonus);

    /***** Weapons production *****/
    /**
     * @return array
     */
    public function getWeaponsProduction($level, $weapon);

    /**
     * @param array $weaponsProduction
     */
    public function setWeaponsProduction(array $weaponsProduction);

    /***** Weapons resources cost bonus *****/
    /**
     * @return array
     */
    public function getWeaponsResourcesCostBonus($level);

    /**
     * @param array $weaponsResourcesCostBonus
     */
    public function setWeaponsResourcesCostBonus(array $weaponsResourcesCostBonus);

    /***** Weapons build time bonus *****/
    /**
     * @return array
     */
    public function getWeaponsBuildTimeBonus($level);

    /**
     * @param array $weaponsBuildTimeBonus
     */
    public function setWeaponsBuildTimeBonus(array $weaponsBuildTimeBonus);

    /***** Buildings required *****/
    /**
     * @return array
     */
    public function getBuildingsRequired($level, $building);

    /**
     * @param array $buildingsRequired
     */
    public function setBuildingsRequired(array $buildingsRequired);

    /***** Per town limit *****/
    /**
     * @return integer
     */
    public function getPerTownLimit();

    /**
     * @param integer $perTownLimit
     */
    public function setPerTownLimit($perTownLimit);

    /***** Per country limit *****/
    /**
     * @return integer
     */
    public function getPerCountryLimit();

    /**
     * @param integer $perCountryLimit
     */
    public function setPerCountryLimit($perCountryLimit);

    /***** Per planet limit *****/
    /**
     * @return integer
     */
    public function getPerPlanetLimit();

    /**
     * @param integer $perPlanetLimit
     */
    public function setPerPlanetLimit($perPlanetLimit);

    /***** Limit *****/
    /**
     * @return integer
     */
    public function getLimit();

    /**
     * @param integer $limit
     */
    public function setLimit($limit);

    /***** Available terrain types *****/
    /**
     * @return array
     */
    public function getAvailableTerrainTypes();

    /**
     * @param array $availableTerrainTypes
     */
    public function setAvailableTerrainTypes(array $availableTerrainTypes);
}
