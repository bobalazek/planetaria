<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Planet Entity
 *
 * @ORM\Table(name="planets")
 * @ORM\Entity(repositoryClass="Application\Repository\PlanetRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class PlanetEntity extends AbstractAdvancedEntity
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
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean")
     */
    protected $public = false;

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
     * @ORM\OneToMany(targetEntity="Application\Entity\TileEntity", mappedBy="planet")
     */
    protected $tiles;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\TownEntity", mappedBy="planet")
     */
    protected $towns;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->tiles = new ArrayCollection();
    }

    /*** Public ***/
    /**
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->getPublic();
    }

    /**
     * @param boolean $public
     *
     * @return PlanetEntity
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /*** Towns ***/
    /**
     * @return ArrayCollection
     */
    public function getTiles()
    {
        return $this->times->toArray();
    }

    /**
     * @param ArrayCollection $tiles
     *
     * @return PlanetEntity
     */
    public function setTiles($tiles)
    {
        $this->tiles = $tiles;

        return $this;
    }

    /*** Towns ***/
    /**
     * @return ArrayCollection
     */
    public function getTowns()
    {
        return $this->towns->toArray();
    }

    /**
     * @param ArrayCollection $towns
     *
     * @return CountryEntity
     */
    public function setTowns($towns)
    {
        $this->towns = $towns;

        return $this;
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
            in_array('name', $fields)
        ) {
            $data['name'] = $this->getName();
        }

        if (
            in_array('*', $fields) ||
            in_array('slug', $fields)
        ) {
            $data['slug'] = $this->getSlug();
        }

        if (
            in_array('*', $fields) ||
            in_array('description', $fields)
        ) {
            $data['description'] = $this->getDescription();
        }

        return $data;
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
