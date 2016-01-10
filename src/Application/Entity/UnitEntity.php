<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Unit Entity
 *
 * @ORM\Table(name="units")
 * @ORM\Entity(repositoryClass="Application\Repository\UnitRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UnitEntity extends AbstractAdvancedEntity
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
     * How much can the building handle? A.k.a. hit points or damage points.
     *
     * @var integer
     *
     * @ORM\Column(name="health_points", type="integer")
     */
    protected $healthPoints = 100;

    /**
     * @var integer
     *
     * @ORM\Column(name="attack_points", type="integer")
     */
    protected $attackPoints = 20;

    /**
     * @var integer
     *
     * @ORM\Column(name="defense_points", type="integer")
     */
    protected $defensePoints = 10;

    /**
     * How much capacity does it use?
     *
     * @var integer
     *
     * @ORM\Column(name="capacity", type="integer")
     */
    protected $capacity = 1;

    /**
     * How long does the contruction of this unit take (in seconds)?
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\UnitResourceEntity", mappedBy="unit", cascade={"all"}, orphanRemoval=true)
     */
    protected $unitResources;

    /**
     * @ORM\ManyToOne(targetEntity="CountryEntity", inversedBy="units")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    protected $country;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->unitResources = new ArrayCollection();
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
     * @return UnitEntity
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * @return UnitEntity
     */
    public function setHealthPoints($healthPoints)
    {
        $this->healthPoints = $healthPoints;

        return $this;
    }

    /*** Attack Points ***/
    /**
     * @return integer
     */
    public function getAttackPoints()
    {
        return $this->attackPoints;
    }

    /**
     * @param integer $attackPoints
     *
     * @return UnitEntity
     */
    public function setAttackPoints($attackPoints)
    {
        $this->attackPoints = $attackPoints;

        return $this;
    }

    /*** Defense Points ***/
    /**
     * @return integer
     */
    public function getDefensePoints()
    {
        return $this->defensePoints;
    }

    /**
     * @param integer $defensePoints
     *
     * @return UnitEntity
     */
    public function setDefensePoints($defensePoints)
    {
        $this->defensePoints = $defensePoints;

        return $this;
    }

    /*** Capacity ***/
    /**
     * @return integer
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param integer $capacity
     *
     * @return ItemEntity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /*** Build time ***/
    /**
     * @return integer
     */
    public function getBuildTime()
    {
        return $this->healthPoints;
    }

    /**
     * @param integer $buildTime
     *
     * @return UnitEntity
     */
    public function setBuildTime($buildTime)
    {
        $this->buildTime = $buildTime;

        return $this;
    }

    /*** Unit Resources ***/
    /**
     * @return ArrayCollection
     */
    public function getUnitResources()
    {
        return $this->unitResources->toArray();
    }

    /**
     * @param ArrayCollection $unitResources
     *
     * @return UnitEntity
     */
    public function setUnitResources($unitResources)
    {
        foreach ($unitResources as $unitResource) {
            $unitResource->setUnit($this);
        }

        $this->unitResources = $unitResources;

        return $this;
    }

    /**
     * @param UnitResourceEntity $unitResource
     *
     * @return UnitEntity
     */
    public function addUnitResource(UnitResourceEntity $unitResource)
    {
        if (!$this->unitResources->contains($unitResource)) {
            $unitResource->setUnit($this);
            $this->unitResources->add($unitResource);
        }

        return $this;
    }

    /**
     * @param UnitResourceEntity $unitResource
     *
     * @return UnitEntity
     */
    public function removeUnitResource(UnitResourceEntity $unitResource)
    {
        $unitResource->setUnit(null);
        $this->unitResources->removeElement($unitResource);

        return $this;
    }

    /*** Country ***/
    /**
     * @return CountryEntity
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param CountryEntity $country
     *
     * @return UnitEntity
     */
    public function setCountry(CountryEntity $country)
    {
        $this->country = $country;

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
