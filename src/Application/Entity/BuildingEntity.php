<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Building Entity
 *
 * @ORM\Table(name="buildings")
 * @ORM\Entity(repositoryClass="Application\Repository\BuildingRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class BuildingEntity extends AbstractAdvancedEntity
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=32)
     */
    protected $type;

    /**
     * Examples: 1x1, 2x2, 3x3, 4x4, 1x2 (first: x - horizontal; second: y - vertical)
     *
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=16)
     */
    protected $size;

    /**
     * How much can the building handle? A.k.a. hit points or damage points.
     *
     * @var integer
     *
     * @ORM\Column(name="health_points", type="integer")
     */
    protected $healthPoints = 1000;

    /**
     * How much population capacity do they give?
     *
     * @var integer
     *
     * @ORM\Column(name="population_capacity", type="integer")
     */
    protected $populationCapacity = 10;

    /**
     * How much storage capacity do they give?
     *
     * @var integer
     *
     * @ORM\Column(name="storage_capacity", type="integer")
     */
    protected $storageCapacity = 100;

    /**
     * How long does the contruction of this building take (in seconds)?
     *
     * @var integer
     *
     * @ORM\Column(name="build_time", type="integer")
     */
    protected $buildTime = 30;

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
     * How much of what does this building cost?
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\BuildingResourceEntity", mappedBy="building", cascade={"all"}, orphanRemoval=true)
     */
    protected $buildingResources;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\TownBuildingEntity", mappedBy="building", cascade={"all"})
     */
    protected $townBuildings;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->buildingResources = new ArrayCollection();
        $this->townBuildings = new ArrayCollection();
    }

    /*** Type ***/
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return BuildingEntity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /*** Size ***/
    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     *
     * @return BuildingEntity
     */
    public function setSize($size)
    {
        $this->size = $size;

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
     * @return BuildingEntity
     */
    public function setHealthPoints($healthPoints)
    {
        $this->healthPoints = $healthPoints;

        return $this;
    }

    /*** Population Capacity ***/
    /**
     * @return integer
     */
    public function getPopulationCapacity()
    {
        return $this->populationCapacity;
    }

    /**
     * @param integer $populationCapacity
     *
     * @return BuildingEntity
     */
    public function setPopulationCapacity($populationCapacity)
    {
        $this->populationCapacity = $populationCapacity;

        return $this;
    }

    /*** Storage Capacity ***/
    /**
     * @return integer
     */
    public function getStorageCapacity()
    {
        return $this->storageCapacity;
    }

    /**
     * @param integer $storageCapacity
     *
     * @return BuildingEntity
     */
    public function setStorageCapacity($storageCapacity)
    {
        $this->storageCapacity = $storageCapacity;

        return $this;
    }

    /*** Build time ***/
    /**
     * @return integer
     */
    public function getBuildTime()
    {
        return $this->buildTime;
    }

    /**
     * @param integer $buildTime
     *
     * @return BuildingEntity
     */
    public function setBuildTime($buildTime)
    {
        $this->buildTime = $buildTime;

        return $this;
    }

    /*** Building Resources ***/
    /**
     * @return ArrayCollection
     */
    public function getBuildingResources()
    {
        return $this->buildingResources->toArray();
    }

    /**
     * @param ArrayCollection $buildingResources
     *
     * @return BuildingEntity
     */
    public function setBuildingResources($buildingResources)
    {
        foreach ($buildingResources as $buildingResource) {
            $buildingResource->setBuilding($this);
        }

        $this->buildingResources = $buildingResources;

        return $this;
    }

    /**
     * @param BuildingResourceEntity $buildingResource
     *
     * @return BuildingEntity
     */
    public function addBuildingResource(BuildingResourceEntity $buildingResource)
    {
        if (!$this->buildingResources->contains($buildingResource)) {
            $buildingResource->setBuilding($this);
            $this->buildingResources->add($buildingResource);
        }

        return $this;
    }

    /**
     * @param BuildingResourceEntity $buildingResource
     *
     * @return BuildingEntity
     */
    public function removeBuildingResource(BuildingResourceEntity $buildingResource)
    {
        $buildingResource->setBuilding(null);
        $this->buildingResources->removeElement($buildingResource);

        return $this;
    }

    /*** Town Buildings ***/
    /**
     * @return ArrayCollection
     */
    public function getTownBuildings()
    {
        return $this->townBuildings->toArray();
    }

    /**
     * @param ArrayCollection $townBuildings
     *
     * @return BuildingEntity
     */
    public function setTownBuildings($townBuildings)
    {
        $this->townBuildings = $townBuildings;

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
