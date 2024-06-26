<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Game\BuildingStatuses;
use Application\Helper;

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
     * @var string
     *
     * @ORM\Column(name="terrain_type", type="string", length=16)
     */
    protected $terrainType;

    /**
     * @ORM\Column(name="status", type="string", length=32)
     */
    protected $status;

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
     * Because a building can be multiple sizes big, we need to split them into multiple tiles.
     * In this variable we'll define which section of the building that is.
     * Example:
     *   - If building size is 1x1:
     *     - '1x1' (if the building is 1x1 and requires only 1 tile)
     *   - If building size is 2x2, 3x3, ... then you need to think of it, as the COORDINATES SYSTEM:
     *     - '1x1' would be the LOWER LEFT part of a 2x2 building
     *     - '2x2' would be the UPPER RIGHT part of a 2x2 building
     *     - '3x1' would be the UPPER CENTER part of a 3x3 building
     *     - '2x2' would be the MIDDLE CENTER part of a 3x3 building
     *
     * Visual:
     * - 2x2
     * -------------
     * | 1x2 | 2x2 |
     * -------------
     * | 1x1 | 2x1 |
     * -------------
     *
     * - 3x3
     * -------------------
     * | 1x3 | 2x3 | 3x3 |
     * -------------------
     * | 1x2 | 2x2 | 3x2 |
     * -------------------
     * | 1x1 | 2x1 | 3x1 |
     * -------------------
     *
     * @var string
     *
     * @ORM\Column(name="building_section", type="string", length=16, nullable=true)
     */
    protected $buildingSection;

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
     * @ORM\ManyToOne(targetEntity="Application\Entity\TownBuildingEntity", inversedBy="tiles")
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

    /*** Terrain type ***/
    /**
     * @return string
     */
    public function getTerrainType()
    {
        return $this->terrainType;
    }

    /**
     * @param string $terrainType
     *
     * @return TileEntity
     */
    public function setTerrainType($terrainType)
    {
        $this->terrainType = $terrainType;

        return $this;
    }

    /*** Status ***/
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return TileEntity
     */
    public function setStatus($status)
    {
        $this->status = $status;

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

    /*** Building section ***/
    /**
     * @return string
     */
    public function getBuildingSection()
    {
        return $this->buildingSection;
    }

    /**
     * @param string $buildingSection
     *
     * @return TileEntity
     */
    public function setBuildingSection($buildingSection)
    {
        $this->buildingSection = $buildingSection;

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
    public function setTownBuilding(TownBuildingEntity $townBuilding = null)
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

    /*** Town Building Image ***/
    /**
     * @param boolean $forceOperational Forces the town building status to be 'operational'. May be useful, when we need the "real" image of the building (regarding what the current status is).
     *
     * @return string
     */
    public function getTownBuildingImage($forceOperational = false)
    {
        $townBuilding = $this->getTownBuilding();
        $buildingObject = $townBuilding->getBuildingObject();
        $buildingObjectSlug = $buildingObject->getSlug();
        $buildingObjectSize = $buildingObject->getSize();
        $townBuildingStatus = $townBuilding->getStatus();
        $buildingSection = $this->getBuildingSection();

        if ($forceOperational) {
            $townBuildingStatus = BuildingStatuses::OPERATIONAL;
        }

        if ($townBuildingStatus === BuildingStatuses::CONSTRUCTING) {
            return '_constructing/'.$buildingObjectSize.'/'.$buildingSection.'.png';
        }

        if ($townBuildingStatus === BuildingStatuses::DESTROYED) {
            return '_destroyed/'.$buildingObjectSize.'/'.$buildingSection.'.png';
        }

        return $buildingObjectSlug.'/'.$townBuildingStatus.'/'.$buildingSection.'.png';
    }

    /**
     * @param array $fields Which fields should it show?
     *
     * @return array
     */
    public function toArray($fields = array('*'))
    {
        $data = array();

        if (
            in_array('*', $fields) ||
            in_array('id', $fields)
        ) {
            $data['id'] = $this->getId();
        }

        if (
            in_array('*', $fields) ||
            in_array('terrain_type', $fields)
        ) {
            $data['terrain_type'] = $this->getTerrainType();
        }

        if (
            in_array('*', $fields) ||
            in_array('status', $fields)
        ) {
            $data['status'] = $this->getStatus();
        }

        if (
            in_array('*', $fields) ||
            in_array('background_image', $fields)
        ) {
            $data['background_image'] = $this->getBackgroundImage();
        }

        if (
            in_array('*', $fields) ||
            in_array('coordinates', $fields)
        ) {
            $data['coordinates'] = $this->getCoordinates();
        }

        if (
            in_array('*', $fields) ||
            in_array('coordinates_x', $fields)
        ) {
            $data['coordinates_x'] = $this->getCoordinatesX();
        }

        if (
            in_array('*', $fields) ||
            in_array('coordinates_y', $fields)
        ) {
            $data['coordinates_y'] = $this->getCoordinatesY();
        }

        if (
            in_array('*', $fields) ||
            in_array('town_building', $fields) ||
            Helper::strpos_array($fields, 'town_building.') !== false
        ) {
            $townBuildingFields = array('*');

            if ($index = Helper::strpos_array($fields, 'town_building.')) {
                $field = $fields[$index];
                $townBuildingFields = explode(
                    ',',
                    trim(
                        str_replace(
                            'town_building.',
                            '',
                            $field
                        ),
                        '{}'
                    )
                );
            }

            $data['town_building'] = $this->getTownBuilding() !== null
                ? $this->getTownBuilding()->toArray($townBuildingFields)
                : null
            ;
        }

        if (
            in_array('*', $fields) ||
            in_array('building_section', $fields)
        ) {
            $data['building_section'] = $this->getBuildingSection();
        }

        if (
            in_array('*', $fields) ||
            in_array('planet', $fields) ||
            Helper::strpos_array($fields, 'planet.') !== false
        ) {
            $planetFields = array('*');

            if ($index = Helper::strpos_array($fields, 'planet.')) {
                $field = $fields[$index];
                $planetFields = explode(
                    ',',
                    trim(
                        str_replace(
                            'planet.',
                            '',
                            $field
                        ),
                        '{}'
                    )
                );
            }

            $data['planet'] = $this->getPlanet()->toArray($planetFields);
        }

        if (
            in_array('*', $fields) ||
            in_array('tile_resources', $fields) ||
            Helper::strpos_array($fields, 'tile_resources.') !== false
        ) {
            $tileResourcesFields = array('*');

            if ($index = Helper::strpos_array($fields, 'tile_resources.')) {
                $field = $fields[$index];
                $tileResourcesFields = explode(
                    ',',
                    trim(
                        str_replace(
                            'tile_resources.',
                            '',
                            $field
                        ),
                        '{}'
                    )
                );
            }

            $tileResources = array();
            $tileResourcesCollection = $this->getTileResources();
            if (!empty($tileResourcesCollection)) {
                foreach ($tileResourcesCollection as $tileResource) {
                    $tileResources[] = $tileResource->toArray($tileResourcesFields);
                }
            }

            $data['tile_resources'] = $tileResources;
        }

        return $data;
    }

    /*** Occupied ***/
    /**
     * @return boolean
     */
    public function isOccupied()
    {
        return $this->getTownBuilding() !== null;
    }

    /*** Buildable ***/
    /**
     * @return boolean
     */
    public function isBuildable()
    {
        return !$this->isOccupied();
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
