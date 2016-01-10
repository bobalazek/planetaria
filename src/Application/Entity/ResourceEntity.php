<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Resource Entity
 *
 * @ORM\Table(name="resources")
 * @ORM\Entity(repositoryClass="Application\Repository\ResourceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ResourceEntity extends AbstractAdvancedEntity
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
     * @ORM\OneToMany(targetEntity="Application\Entity\TownResourceEntity", mappedBy="resource", cascade={"all"})
     */
    protected $townResources;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\BuildingResourceEntity", mappedBy="resource", cascade={"all"})
     */
    protected $buildingResources;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\UnitResourceEntity", mappedBy="resource", cascade={"all"})
     */
    protected $unitResources;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\ItemResourceEntity", mappedBy="resource", cascade={"all"})
     */
    protected $itemResources;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\DistrictResourceEntity", mappedBy="resource", cascade={"all"})
     */
    protected $districtResources;

    /*** Town Resources ***/
    /**
     * @return ArrayCollection
     */
    public function getTownResources()
    {
        return $this->townResources->toArray();
    }

    /**
     * @param ArrayCollection $townResources
     *
     * @return ResourceEntity
     */
    public function setTownResources($townResources)
    {
        $this->townResources = $townResources;

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
     * @return ResourceEntity
     */
    public function setBuildingResources($buildingResources)
    {
        $this->buildingResources = $buildingResources;

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
     * @return ResourceEntity
     */
    public function setUnitResources($unitResources)
    {
        $this->unitResources = $unitResources;

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
     * @return ResourceEntity
     */
    public function setItemResources($itemResources)
    {
        $this->itemResources = $itemResources;

        return $this;
    }

    /*** District Resources ***/
    /**
     * @return ArrayCollection
     */
    public function getDistrictResources()
    {
        return $this->districtResources->toArray();
    }

    /**
     * @param ArrayCollection $districtResources
     *
     * @return ResourceEntity
     */
    public function setDistrictResources($districtResources)
    {
        $this->districtResources = $districtResources;

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
