<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tile Entity
 *
 * @ORM\Table(name="tiles", uniqueConstraints={@ORM\UniqueConstraint(columns={"coordinates_x", "coordinates_y", "planet_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\TileRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TileEntity extends AbstractBasicEntity
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
     * water, land, ... ?
     *
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=16)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="background_image", type="string", length=255)
     */
    protected $backgroundImage;

    /**
     * On a 2D map that would be the bottom left (start) pixel of that building.
     *
     * @var integer
     *
     * @ORM\Column(name="coordinates_x", type="integer")
     */
    protected $coordinatesX = 0;

    /**
     * On a 2D map that would be the bottom left (start) pixel of that building.
     *
     * @var integer
     *
     * @ORM\Column(name="coordinates_y", type="integer")
     */
    protected $coordinatesY = 0;
    
    /**
     * Can a building be build on it?
     *
     * @var integer
     *
     * @ORM\Column(name="buildable", type="boolean")
     */
    protected $buildable = true;

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
     * @ORM\OneToOne(targetEntity="Application\Entity\TownBuildingEntity", inversedBy="tile")
     * @ORM\JoinColumn(name="town_building_id", referencedColumnName="id")
     */
    protected $townBuilding;

    /**
     * @ORM\ManyToOne(targetEntity="PlanetEntity", inversedBy="tiles")
     * @ORM\JoinColumn(name="planet_id", referencedColumnName="id")
     */
    protected $planet;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\TileResourceEntity", mappedBy="tile", cascade={"all"}, orphanRemoval=true)
     */
    protected $tileResources;

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
     * @return TileEntity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /*** Background Image ***/
    /**
     * @return string
     */
    public function getBackgroundImage()
    {
        return $this->backgroundImage;
    }

    /**
     * @param string $backgroundImage
     *
     * @return TileEntity
     */
    public function setBackgroundImage($backgroundImage)
    {
        $this->backgroundImage = $backgroundImage;

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
     * @return TileEntity
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
     * @return TileEntity
     */
    public function setCoordinatesY($coordinatesY)
    {
        $this->coordinatesY = $coordinatesY;

        return $this;
    }

    /*** Coordinates ***/
    /**
     * @return string
     */
    public function getCoordinates()
    {
        return $this->getCoordinatesX().','.$this->getCoordinatesY();
    }
    
    /*** Buildable ***/
    /**
     * @return boolean
     */
    public function getBuildable()
    {
        return $this->buildable;
    }
    
    /**
     * @return boolean
     */
    public function isBuildable()
    {
        return $this->buildable;
    }

    /**
     * @param boolean $buildable
     *
     * @return TileEntity
     */
    public function setBuildable($buildable)
    {
        $this->buildable = $buildable;

        return $this;
    }

    /*** Town Building ***/
    /**
     * @return TownBuildingEntity
     */
    public function getTownBuilding()
    {
        return $this->townBuilding;
    }

    /**
     * @param TownBuilding $townBuilding
     *
     * @return TileEntity
     */
    public function setTownBuilding(MapTileEntity $townBuilding)
    {
        $this->townBuilding = $townBuilding;

        return $this;
    }

    /*** Planet ***/
    /**
     * @return PlanetEntity
     */
    public function getPlanet()
    {
        return $this->planet;
    }

    /**
     * @param PlanetEntity $planet
     *
     * @return TileEntity
     */
    public function setPlanet(PlanetEntity $planet)
    {
        $this->planet = $planet;

        return $this;
    }
    
    /*** Tile resources ***/
    /**
     * @return ArrayCollection
     */
    public function getTileResources()
    {
        return $this->tileResources->toArray();
    }

    /**
     * @param ArrayCollection $tileResources
     *
     * @return TileEntity
     */
    public function setTileResources($tileResources)
    {
        foreach ($tileResources as $tileResource) {
            $tileResource->setTile($this);
        }

        $this->tileResources = $tileResources;

        return $this;
    }

    /**
     * @param TileResourceEntity $tileResource
     *
     * @return TileEntity
     */
    public function addTileResource(TileResourceEntity $tileResource)
    {
        if (!$this->tileResources->contains($tileResource)) {
            $tileResource->setTile($this);
            $this->tileResources->add($tileResource);
        }

        return $this;
    }

    /**
     * @param TileResourceEntity $tileResource
     *
     * @return TileEntity
     */
    public function removeTileResource(TileResourceEntity $tileResource)
    {
        $tileResource->setTile(null);
        $this->tileResources->removeElement($tileResource);

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
