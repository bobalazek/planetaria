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
     * @ORM\OneToMany(targetEntity="Application\Entity\TownEntity", mappedBy="town")
     */
    protected $towns;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->tiles = new ArrayCollection();
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
