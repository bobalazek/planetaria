<?php

namespace Application\Game\Building;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
interface WeaponInterface
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

    /***** Type *****/
    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType($type);

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
