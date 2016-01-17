<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Game\Buildings\AbstractBuilding;
use Application\Game\Buildings;
use Application\Game\BuildingStatuses;

/**
 * Town Building Entity
 *
 * @ORM\Table(name="town_buildings")
 * @ORM\Entity(repositoryClass="Application\Repository\TownBuildingRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownBuildingEntity extends AbstractBasicEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="building", type="string", length=64)
     */
    protected $building;

    /**
     * At which level is the building?
     *
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    protected $level = 0;

    /**
     * How much can the buidling handle? A.k.a. hit points or damage points.
     *
     * @var integer
     *
     * @ORM\Column(name="health_points", type="integer")
     */
    protected $healthPoints = 1000;

    /**
     * If damage, how much is it left?
     *
     * @var integer
     *
     * @ORM\Column(name="health_points_left", type="integer")
     */
    protected $healthPointsLeft = 1000;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_constructed", type="datetime")
     */
    protected $timeConstructed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_created", type="datetime")
     */
    protected $timeCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_updated", type="datetime")
     */
    protected $timeUpdated;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\TownEntity", inversedBy="townBuildings")
     * @ORM\JoinColumn(name="town_id", referencedColumnName="id")
     */
    protected $town;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\TileEntity", mappedBy="townBuilding", cascade={"all"}, orphanRemoval=true)
     **/
    protected $tiles;

    /*** Building ***/
    /**
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param string $building
     *
     * @return TownBuildingEntity
     */
    public function setBuilding($building)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * @return AbstractBuilding
     */
    public function getBuildingObject()
    {
        $className = 'Application\\Game\\Building\\'.
            Buildings::getClassName($this->getBuilding())
        ;

        return new $className();
    }

    /*** Level ***/
    /**
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param integer $level
     *
     * @return TownBuildingEntity
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /*** Health Points ***/
    /**
     * @return integer
     */
    public function getHealthPoints()
    {
        return $this->healthPoints;
    }

    /**
     * @param integer $healthPoints
     *
     * @return TownBuildingEntity
     */
    public function setHealthPoints($healthPoints)
    {
        $this->healthPoints = $healthPoints;

        return $this;
    }

    /*** Health Points Left ***/
    /**
     * @return integer
     */
    public function getHealthPointsLeft()
    {
        return $this->healthPointsLeft;
    }

    /**
     * @param integer $healthPointsLeft
     *
     * @return TownBuildingEntity
     */
    public function setHealthPointsLeft($healthPointsLeft)
    {
        $this->healthPointsLeft = $healthPointsLeft;

        return $this;
    }

    /*** Town ***/
    /**
     * @return TownEntity
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param TownEntity $town
     *
     * @return TownBuildingEntity
     */
    public function setTown(TownEntity $town)
    {
        $this->town = $town;

        return $this;
    }

    /*** Tile ***/
    /**
     * @return ArrayCollection
     */
    public function getTiles()
    {
        return $this->tiles;
    }

    /**
     * @param ArrayCollection $tiles
     *
     * @return TownBuildingEntity
     */
    public function setTiles($tiles)
    {
        $this->tiles = $tiles;

        return $this;
    }

    /***** Resources production *****/
    /**
     * @todo: Take damages & stuff into consideration
     * @todo: Do NOT count in production froum buildings, currently in construction (timeConstructed < currentDatetime)
     *
     * @return array
     */
    public function getResourcesProduction()
    {
        if (!$this->isOperational()) {
            return array();
        }

        $level = $this->getLevel();
        $resourcesProduction = array();

        $buildingResourcesProduction = $this->getBuildingObject()->getResourcesProduction();

        if (isset($buildingResourcesProduction[$level])) {
            $resourcesProduction = $buildingResourcesProduction[$level];
        }

        return $resourcesProduction;
    }

    /**
     * @todo: Take damages & stuff into consideration
     *
     * @return array
     */
    public function getResourcesCapacity()
    {
        if (!$this->isOperational()) {
            return array();
        }

        $level = $this->getLevel();
        $resourcesCapacity = array();

        $buildingResourcesCapacity = $this->getBuildingObject()->getResourcesCapacity();

        if (isset($buildingResourcesCapacity[$level])) {
            $resourcesCapacity = $buildingResourcesCapacity[$level];
        }

        return $resourcesCapacity;
    }

    /**
     * @todo: Take damages & stuff into consideration
     *
     * @return array
     */
    public function getPopulationCapacity()
    {
        if (!$this->isOperational()) {
            return array();
        }

        $level = $this->getLevel();
        $populationCapacity = array();

        $buildingPopulationCapacity = $this->getBuildingObject()->getPopulationCapacity();

        if (isset($buildingPopulationCapacity[$level])) {
            $populationCapacity = $buildingPopulationCapacity[$level];
        }

        return $populationCapacity;
    }

    /*** Coordinates ***/
    /**
     * @return integer
     */
    public function getCoordinates()
    {
        return $this->getCoordinatesX().','.$this->getCoordinatesY();
    }

    /**
     * @return integer
     */
    public function getCoordinatesX()
    {
        $tiles = $this->getTiles();

        return $tiles[0]->getCoordinatesX();
    }

    /**
     * @return integer
     */
    public function getCoordinatesY()
    {
        $tiles = $this->getTiles();

        return $tiles[0]->getCoordinatesY();
    }

    /*** Time constructed ***/
    /**
     * @return \DateTime
     */
    public function getTimeConstructed()
    {
        return $this->timeConstructed;
    }

    /**
     * @param \DateTime $timeConstructed
     *
     * @return TownBuildingEntity
     */
    public function setTimeConstructed(\DateTime $timeConstructed = null)
    {
        $this->timeConstructed = $timeConstructed;

        return $this;
    }

    /*** Operational ***/
    /**
     * @return boolean
     */
    public function isOperational()
    {
        return $this->getStatus() === BuildingStatuses::OPERATIONAL;
    }

    /*** Status ***/
    /**
     * @return string
     */
    public function getStatus()
    {
        $currentDatetime = new \Datetime();
        $timeConstructed = $this->getTimeConstructed();
        $healthPointsLeft = $this->getHealthPointsLeft();

        // Building isn't yet build, so it can not produce anything.
        if ($timeConstructed > $currentDatetime) {
            return BuildingStatuses::CONSTRUCTING;
        }

        if ($healthPointsLeft === 0) {
            return BuildingStatuses::DESTROYED;
        }

        return BuildingStatuses::OPERATIONAL;
    }

    /**
     * @return boolean
     */
    public function isUpgradable()
    {
        $buildingObject = $this->getBuildingObject();

        return $this->getLevel() < $buildingObject->getMaximumLevel();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setTimeUpdated(new \DateTime('now'));
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setTimeCreated(new \DateTime('now'));
        $this->setTimeUpdated(new \DateTime('now'));
    }
}
