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
class CountryEntity extends AbstractAdvancedWithImageUploadEntity
{
    /***** Joining statuses *****/
    const JOINING_STATUS_OPEN = 'open';
    const JOINING_STATUS_INVITE_ONLY = 'invite_only';
    const JOINING_STATUS_CLOSED = 'closed';

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
     * @ORM\Column(name="image_url", type="text", nullable=true)
     */
    protected $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="joining_status", type="string", length=32)
     */
    protected $joiningStatus = 'closed';

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
     * @ORM\OneToMany(targetEntity="Application\Entity\TownEntity", mappedBy="country")
     */
    protected $towns;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserEntity", inversedBy="countries")
     */
    protected $user;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->towns = new ArrayCollection();
    }

    /*** Joining status ***/
    /**
     * @return string
     */
    public function getJoiningStatus()
    {
        return $this->joiningStatus;
    }

    /**
     * @param string $joiningStatus
     *
     * @return CountryEntity
     */
    public function setJoiningStatus($joiningStatus)
    {
        $this->joiningStatus = $joiningStatus;

        return $this;
    }

    /*** Joining statuses ***/
    /**
     * @return array
     */
    public static function getJoiningStatuses()
    {
        return array(
            self::JOINING_STATUS_OPEN => 'Open',
            self::JOINING_STATUS_INVITE_ONLY => 'Invite only',
            self::JOINING_STATUS_CLOSED => 'Closed',
        );
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
     * @return integer
     */
    public function getTownsLimit()
    {
        return 1;
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

    /*** Town buildings ***/
    /**
     * @return array
     */
    public function getTownBuildings()
    {
        $towns = $this->getTowns();
        $townBuildings = array();

        if (!empty($towns)) {
            foreach ($towns as $town) {
                $townTownBuildings = $town->getTownBuildings();

                if (!empty($townTownBuildings)) {
                    foreach ($townTownBuildings as $townTownBuilding) {
                        $townBuildings[] = $townTownBuilding;
                    }
                }
            }
        }

        return $townBuildings;
    }
    
    /***** Flag image url *****/
    /**
     * @return string
     */
    public function getFlagImageUrl($baseUrl)
    {
        $imageUrl = $this->getImageUrl();
        if ($imageUrl) {
            return $imageUrl;
        }

        // To-Do: Throw a warning or something, when no $baseUrl is given

        return $baseUrl.$this->getPlaceholderImageUri();
    }
    
    /**
     * @return string
     */
    public function getPlaceholderImageUri()
    {
        return '/assets/images/countries/placeholder.png';
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
