<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="building", type="string", length=32)
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
    protected $healthPoints;

    /**
     * If damage, how much is it left?
     *
     * @var integer
     *
     * @ORM\Column(name="health_points_left", type="integer")
     */
    protected $healthPointsLeft;

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
     * @ORM\OneToOne(targetEntity="Application\Entity\MapTileEntity", mappedBy="townBuilding")
     **/
    protected $mapTile;

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
    
    /*** Map Tile ***/
    /**
     * @return MapTileEntity
     */
    public function getMapTile()
    {
        return $this->mapTile;
    }

    /**
     * @param MapTileEntity $mapTile
     *
     * @return TownBuildingEntity
     */
    public function setMapTile(MapTileEntity $mapTile)
    {
        $this->mapTile = $mapTile;

        return $this;
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
