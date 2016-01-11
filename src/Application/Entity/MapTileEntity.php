<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Map Tile Entity
 *
 * @ORM\Table(name="map_tiles")
 * @ORM\Entity(repositoryClass="Application\Repository\MapTileRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class MapTileEntity extends AbstractBasicEntity
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    protected $image;

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
     * @ORM\OneToOne(targetEntity="Application\Entity\TownBuildingEntity", inversedBy="mapTile")
     * @ORM\JoinColumn(name="town_building_id", referencedColumnName="id")
     */
    protected $townBuilding;
    
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
     * @return TownBuildingEntity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
    
    /*** Image ***/
    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return TownBuildingEntity
     */
    public function setImage($image)
    {
        $this->image = $image;

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
     * @return TownBuildingEntity
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
     * @return TownBuildingEntity
     */
    public function setCoordinatesY($coordinatesY)
    {
        $this->coordinatesY = $coordinatesY;

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
     * @return TownBuildingEntity
     */
    public function setTownBuilding(MapTileEntity $townBuilding)
    {
        $this->townBuilding = $townBuilding;

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
