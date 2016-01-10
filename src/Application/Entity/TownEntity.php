<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Town Entity
 *
 * @ORM\Table(name="towns")
 * @ORM\Entity(repositoryClass="Application\Repository\TownRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownEntity extends AbstractAdvancedEntity
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
     * @ORM\ManyToOne(targetEntity="CountryEntity", inversedBy="towns")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    protected $country;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\TownResourceEntity", mappedBy="town", cascade={"all"}, orphanRemoval=true)
     */
    protected $townResources;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\TownBuildingEntity", mappedBy="town", cascade={"all"})
     */
    protected $townBuildings;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->townResources = new ArrayCollection();
        $this->townBuildings = new ArrayCollection();
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
     * @return TownEntity
     */
    public function setCountry(CountryEntity $country)
    {
        $this->country = $country;

        return $this;
    }

    /*** Town resources ***/
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
     * @return TownEntity
     */
    public function setTownResources($townResources)
    {
        foreach ($townResources as $townResource) {
            $townResource->setTown($this);
        }

        $this->townResources = $townResources;

        return $this;
    }

    /**
     * @param TownResourceEntity $townResource
     *
     * @return TownEntity
     */
    public function addTownResource(TownResourceEntity $townResource)
    {
        if (!$this->townResources->contains($townResource)) {
            $townResource->setTown($this);
            $this->townResources->add($townResource);
        }

        return $this;
    }

    /**
     * @param TownResourceEntity $townResource
     *
     * @return TownEntity
     */
    public function removeTownResource(TownResourceEntity $townResource)
    {
        $townResource->setTown(null);
        $this->townResources->removeElement($townResource);

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
