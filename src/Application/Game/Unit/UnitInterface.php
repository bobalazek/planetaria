<?php

namespace Application\Game\Building;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
interface UnitInterface
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

    /***** Maximum level *****/
    /**
     * @return integer
     */
    public function getMaximumLevel();

    /**
     * @param integer $maximumLevel
     */
    public function setMaximumLevel($maximumLevel);

    /***** Capacity *****/
    /**
     * @return integer
     */
    public function getCapacity();

    /**
     * @param array $capacity
     */
    public function setCapacity($capacity);

    /***** Health points *****/
    /**
     * @return array|integer
     */
    public function getHealthPoints($level);

    /**
     * @param array $healthPoints
     */
    public function setHealthPoints(array $healthPoints);

    /***** Attack points *****/
    /**
     * @return array|integer
     */
    public function getAttackPoints($level);

    /**
     * @param array $attackPoints
     */
    public function setAttackPoints(array $attackPoints);

    /***** Defense points *****/
    /**
     * @return array|integer
     */
    public function getDefensePoints($level);

    /**
     * @param array $defensePoints
     */
    public function setDefensePoints(array $defensePoints);

    /***** Speed *****/
    /**
     * @return array|integer
     */
    public function getSpeed($level);

    /**
     * @param array $speed
     */
    public function setSpeed(array $speed);

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
}
