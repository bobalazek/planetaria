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
     * @var \DateTime
     *
     * @ORM\Column(name="time_next_level_upgrade_started", type="datetime", nullable=true)
     */
    protected $timeNextLevelUpgradeStarted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_next_level_upgrade_ends", type="datetime", nullable=true)
     */
    protected $timeNextLevelUpgradeEnds;

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
     * @ORM\OneToMany(targetEntity="Application\Entity\TileEntity", mappedBy="townBuilding")
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

    /*** Next level ***/
    /**
     * @return integer
     */
    public function getNextLevel()
    {
        return $this->getLevel() + 1;
    }

    /*** Health points ***/
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
        if ($healthPoints < 0) {
            $healthPoints = 0;
        }

        $this->healthPoints = $healthPoints;

        return $this;
    }

    /*** Health points total ***/
    /**
     * @return integer
     */
    public function getHealthPointsTotal()
    {
        $buildingObject = $this->getBuildingObject();

        return $buildingObject->getHealthPoints($this->getLevel());
    }

    /*** Health points percentage ***/
    /**
     * @return integer
     */
    public function getHealthPointsPercentage()
    {
        $total = $this->getHealthPointsTotal();
        $current = $this->getHealthPoints();

        if ($total === 0) {
            return 100;
        } elseif ($current === 0) {
            return 0;
        }

        return ceil(($current / $total) * 100);
    }

    /*** Health points percentage color type ***/
    /**
     * @return string
     */
    public function getHealthPointsPercentageColorType()
    {
        $percentage = $this->getHealthPointsPercentage();

        if ($percentage <= 20) {
            return 'danger';
        } elseif ($percentage <= 40) {
            return 'warning';
        }

        return 'success';
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
     * @return string
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

    /*** Time next level upgrade started ***/
    /**
     * @return \DateTime
     */
    public function getTimeNextLevelUpgradeStarted()
    {
        return $this->timeNextLevelUpgradeStarted;
    }

    /**
     * @param \DateTime $timeNextLevelUpgradeStarted
     *
     * @return TownBuildingEntity
     */
    public function setTimeNextLevelUpgradeStarted(\DateTime $timeNextLevelUpgradeStarted = null)
    {
        $this->timeNextLevelUpgradeStarted = $timeNextLevelUpgradeStarted;

        return $this;
    }

    /*** Time next level upgrade ends ***/
    /**
     * @return \DateTime
     */
    public function getTimeNextLevelUpgradeEnds()
    {
        return $this->timeNextLevelUpgradeEnds;
    }

    /**
     * @param \DateTime $timeNextLevelUpgradeEnds
     *
     * @return TownBuildingEntity
     */
    public function setTimeNextLevelUpgradeEnds(\DateTime $timeNextLevelUpgradeEnds = null)
    {
        $this->timeNextLevelUpgradeEnds = $timeNextLevelUpgradeEnds;

        return $this;
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
    public function setTimeConstructed(\DateTime $timeConstructed)
    {
        $this->timeConstructed = $timeConstructed;

        return $this;
    }

    /*** Status ***/
    /**
     * @return string
     */
    public function getStatus()
    {
        $currentDatetime = new \Datetime();
        $timeConstructed = $this->getTimeConstructed();
        $healthPoints = $this->getHealthPoints();

        // Building isn't yet build, so it can not produce anything.
        if ($timeConstructed > $currentDatetime) {
            return BuildingStatuses::CONSTRUCTING;
        }

        if ($healthPoints === 0) {
            return BuildingStatuses::DESTROYED;
        }

        return BuildingStatuses::OPERATIONAL;
    }

    /*** At maximum level ***/
    /**
     * @return boolean
     */
    public function isAtMaximumLevel()
    {
        $buildingObject = $this->getBuildingObject();

        return $this->getLevel() >= $buildingObject->getMaximumLevel();
    }

    /*** Operational ***/
    /**
     * @return boolean
     */
    public function isOperational()
    {
        return $this->getStatus() === BuildingStatuses::OPERATIONAL;
    }

    /*** Upgradable ***/
    /**
     * @return boolean
     */
    public function isUpgradable()
    {
        return !$this->isAtMaximumLevel();
    }

    /*** Removable ***/
    /**
     * @return boolean
     */
    public function isRemovable()
    {
        if ($this->getBuilding() === Buildings::CAPITOL) {
            return false;
        }

        return true;
    }

    /*** Upgrading ***/
    /**
     * @return boolean
     */
    public function isUpgrading()
    {
        $timeNextLevelUpgradeStarted = $this->getTimeNextLevelUpgradeStarted();
        $timeNextLevelUpgradeEnds = $this->getTimeNextLevelUpgradeEnds();

        return $timeNextLevelUpgradeStarted !== null && $timeNextLevelUpgradeEnds !== null;
    }

    /*** Upgrading progress percentage ***/
    /**
     * @return float
     */
    public function getUpgradingProgressPercentage()
    {
        if ($this->isUpgrading()) {
            $now = strtotime((new \Datetime())->format(DATE_ATOM));
            $start = strtotime($this->getTimeNextLevelUpgradeStarted()->format(DATE_ATOM));
            $end = strtotime($this->getTimeNextLevelUpgradeEnds()->format(DATE_ATOM));

            return ($now - $start) / ($end - $start) * 100;
        }

        return 0;
    }

    /**
     * @return float
     */
    public function getUpgradingProgressPercentagePerSecond()
    {
        if ($this->isUpgrading()) {
            $now = strtotime((new \Datetime())->format(DATE_ATOM));
            $start = strtotime($this->getTimeNextLevelUpgradeStarted()->format(DATE_ATOM));
            $end = strtotime($this->getTimeNextLevelUpgradeEnds()->format(DATE_ATOM));
            $secondsLeft = $end - $now;
            $percentageLeft = 100 - (($now - $start) / ($end - $start) * 100);

            if ($percentageLeft === 0 || $secondsLeft === 0) {
                return 0;
            }

            return $percentageLeft / $secondsLeft;
        }

        return 0;
    }

    /*** Seconds until upgrading done ***/
    /**
     * @return integer|boolean
     */
    public function getSecondsUntilUpgradingDone()
    {
        if ($this->isUpgrading()) {
            $now = new \Datetime();
            $end = $this->getTimeNextLevelUpgradeEnds();

            return strtotime($end->format(DATE_ATOM)) - strtotime($now->format(DATE_ATOM));
        }

        return false;
    }

    /*** Constructing ***/
    /**
     * @return boolean
     */
    public function isConstructing()
    {
        return $this->getStatus() === BuildingStatuses::CONSTRUCTING;
    }

    /*** Constructing progress percentage ***/
    /**
     * @return float
     */
    public function getConstructingProgressPercentage()
    {
        if ($this->isConstructing()) {
            $now = strtotime((new \Datetime())->format(DATE_ATOM));
            $start = strtotime($this->getTimeCreated()->format(DATE_ATOM));
            $end = strtotime($this->getTimeConstructed()->format(DATE_ATOM));

            return ($now - $start) / ($end - $start) * 100;
        }

        return 0;
    }

    /**
     * @return integer|boolean
     */
    public function getSecondsUntilConstructingDone()
    {
        if ($this->isConstructing()) {
            $now = new \Datetime();
            $end = $this->getTimeConstructed();

            return strtotime($end->format(DATE_ATOM)) - strtotime($now->format(DATE_ATOM));
        }

        return false;
    }

    /**
     * @return float
     */
    public function getConstructingProgressPercentagePerSecond()
    {
        if ($this->isConstructing()) {
            $now = strtotime((new \Datetime())->format(DATE_ATOM));
            $start = strtotime($this->getTimeCreated()->format(DATE_ATOM));
            $end = strtotime($this->getTimeConstructed()->format(DATE_ATOM));
            $secondsLeft = $end - $now;
            $percentageLeft = 100 - (($now - $start) / ($end - $start) * 100);

            if ($percentageLeft === 0 || $secondsLeft === 0) {
                return 0;
            }

            return $percentageLeft / $secondsLeft;
        }

        return 0;
    }

    /*** Badge text ***/
    /**
     * @return string
     */
    public function getBadgeText()
    {
        $status = $this->getStatus();

        if ($status === BuildingStatuses::CONSTRUCTING) {
            return '(constructing)';
        }

        return $this->getLevel().
            ($this->isUpgrading() ? ' => '.($this->getNextLevel()).' (upgrading)' : '')
        ;
    }

    /*** Image ***/
    /**
     * @return string
     */
    public function getImage()
    {
        $status = $this->getStatus();
        $building = $this->getBuilding();
        $buildingObject = $this->getBuildingObject();

        if ($status === BuildingStatuses::CONSTRUCTING) {
            return '_constructing/full.png';
        }

        return $buildingObject->getSlug().'/'.$status.'/full.png';
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
