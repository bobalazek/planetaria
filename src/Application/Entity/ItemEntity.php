<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Item Entity
 *
 * @ORM\Table(name="items")
 * @ORM\Entity(repositoryClass="Application\Repository\ItemRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ItemEntity extends AbstractAdvancedEntity
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
     * How much capacity does it use?
     *
     * @var integer
     *
     * @ORM\Column(name="capacity", type="integer")
     */
    protected $capacity = 1;

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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\ItemResourceEntity", mappedBy="item", cascade={"all"}, orphanRemoval=true)
     */
    protected $itemResources;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->itemResources = new ArrayCollection();
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
     * @return ItemEntity
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
     * @return ItemEntity
     */
    public function setHealthPoints($healthPoints)
    {
        $this->healthPoints = $healthPoints;

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
     * @return ItemEntity
     */
    public function setBuildTime($buildTime)
    {
        $this->buildTime = $buildTime;

        return $this;
    }

    /*** Item Resources ***/
    /**
     * @return ArrayCollection
     */
    public function getItemResources()
    {
        return $this->itemResources->toArray();
    }

    /**
     * @param ArrayCollection $itemResources
     *
     * @return ItemEntity
     */
    public function setItemResources($itemResources)
    {
        foreach ($itemResources as $itemResource) {
            $itemResource->setItem($this);
        }

        $this->itemResources = $itemResources;

        return $this;
    }

    /**
     * @param ItemResourceEntity $itemResource
     *
     * @return ItemEntity
     */
    public function addItemResource(ItemResourceEntity $itemResource)
    {
        if (!$this->itemResources->contains($itemResource)) {
            $itemResource->setItem($this);
            $this->itemResources->add($itemResource);
        }

        return $this;
    }

    /**
     * @param ItemResourceEntity $itemResource
     *
     * @return ItemEntity
     */
    public function removeItemResource(ItemResourceEntity $itemResource)
    {
        $itemResource->setItem(null);
        $this->itemResources->removeElement($itemResource);

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
