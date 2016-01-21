<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Country Entity
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity(repositoryClass="Application\Repository\CountryRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class CountryEntity extends AbstractAdvancedEntity
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
     * @ORM\OneToMany(targetEntity="Application\Entity\UserCountryEntity", mappedBy="country", cascade={"all"})
     */
    protected $userCountries;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\TownEntity", mappedBy="country")
     */
    protected $towns;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\CountryUnitEntity", mappedBy="country", cascade={"all"})
     */
    protected $countryUnits;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserEntity", inversedBy="towns")
     */
    protected $user;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->userCountries = new ArrayCollection();
        $this->towns = new ArrayCollection();
        $this->countryUnits = new ArrayCollection();
    }

    /*** User Countries ***/
    /**
     * @return ArrayCollection
     */
    public function getUserCountries()
    {
        return $this->userCountries->toArray();
    }

    /**
     * @param ArrayCollection $userCountries
     *
     * @return CountryEntity
     */
    public function setUserCountries($userCountries)
    {
        $this->userCountries = $userCountries;

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

    /*** Units ***/
    /**
     * @return ArrayCollection
     */
    public function getCountryUnits()
    {
        return $this->countryUnits->toArray();
    }

    /**
     * @param ArrayCollection $countryUnits
     *
     * @return CountryEntity
     */
    public function setCountryUnits($countryUnits)
    {
        $this->countryUnits = $countryUnits;

        return $this;
    }

    /*** User ***/
    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserEntity $user
     *
     * @return CountryEntity
     */
    public function setUser(UserEntity $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
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
