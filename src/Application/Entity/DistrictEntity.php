<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * District Entity
 *
 * @ORM\Table(name="districts", uniqueConstraints={@ORM\UniqueConstraint(columns={"coordinates_x", "coordinates_y"})})
 * @ORM\Entity(repositoryClass="Application\Repository\DistrictRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class DistrictEntity extends AbstractAdvancedEntity
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
     * @ORM\Column(name="type", type="string", length=32, nullable=true)
     */
    protected $type;

    /**
     * On a 2D (world) map that would be the bottom left (start) pixel of that building.
     *
     * @var integer
     *
     * @ORM\Column(name="coordinates_x", type="integer")
     */
    protected $coordinatesX = 0;

    /**
     * On a 2D (world) map that would be the bottom left (start) pixel of that building.
     *
     * @var integer
     *
     * @ORM\Column(name="coordinates_y", type="integer")
     */
    protected $coordinatesY = 0;

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
     * @ORM\OneToMany(targetEntity="Application\Entity\TownBuildingEntity", mappedBy="district", cascade={"all"})
     */
    protected $townBuildings;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\DistrictResourceEntity", mappedBy="district", cascade={"all"}, orphanRemoval=true)
     */
    protected $districtResources;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->townBuildings = new ArrayCollection();
        $this->districtResources = new ArrayCollection();
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
     * @return DistrictEntity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /*** Coordinates X ***/
    /**
     * @return integer
     */
    public function getCoordinatesX()
    {
        return $this->coordinatesX;
    }

    /**
     * @param integer $coordinatesX
     *
     * @return DistrictEntity
     */
    public function setCoordinatesX($coordinatesX)
    {
        $this->coordinatesX = $coordinatesX;

        return $this;
    }

    /*** Coordinates Y ***/
    /**
     * @return integer
     */
    public function getCoordinatesY()
    {
        return $this->coordinatesY;
    }

    /**
     * @param integer $coordinatesY
     *
     * @return DistrictEntity
     */
    public function setCoordinatesY($coordinatesY)
    {
        $this->coordinatesY = $coordinatesY;

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
     * @return DistrictEntity
     */
    public function setDistrictResources($districtResources)
    {
        foreach ($districtResources as $districtResource) {
            $districtResource->setDistrict($this);
        }

        $this->districtResources = $districtResources;

        return $this;
    }

    /**
     * @param DistrictResourceEntity $districtResource
     *
     * @return DistrictEntity
     */
    public function addDistrictResource(DistrictResourceEntity $districtResource)
    {
        if (!$this->districtResources->contains($districtResource)) {
            $districtResource->setDistrict($this);
            $this->districtResources->add($districtResource);
        }

        return $this;
    }

    /**
     * @param DistrictResourceEntity $districtResource
     *
     * @return DistrictEntity
     */
    public function removeDistrictResource(DistrictResourceEntity $districtResource)
    {
        $districtResource->setDistrict(null);
        $this->districtResources->removeElement($districtResource);

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
