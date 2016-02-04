<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Game\Resources;
use Application\Game\Buildings;
use Application\Game\Building\Building;
use Application\Helper;

/**
 * Town Entity
 *
 * @ORM\Table(name="towns")
 * @ORM\Entity(repositoryClass="Application\Repository\TownRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownEntity extends AbstractAdvancedWithImageUploadEntity
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
     * @ORM\Column(name="image_url", type="text", nullable=true)
     */
    protected $imageUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_last_updated_resources", type="datetime", nullable=true)
     */
    protected $timeLastUpdatedResources;

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
     * @ORM\ManyToOne(targetEntity="PlanetEntity", inversedBy="towns")
     * @ORM\JoinColumn(name="planet_id", referencedColumnName="id")
     */
    protected $planet;

    /**
     * @ORM\ManyToOne(targetEntity="CountryEntity", inversedBy="towns")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    protected $country;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserEntity", inversedBy="towns")
     */
    protected $user;

    /**
     * Also known as the town resources storage.
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\TownResourceEntity", mappedBy="town", cascade={"all"}, orphanRemoval=true)
     */
    protected $townResources;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\TownBuildingEntity", mappedBy="town", cascade={"all"}, orphanRemoval=true)
     */
    protected $townBuildings;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\TownUnitEntity", mappedBy="town", cascade={"all"})
     */
    protected $townUnits;

    /**
     * How much is the limit for population?
     *
     * @var integer
     */
    protected $populationCapacity = 0;

    /**
     * How much is the current population of that town?
     *
     * @var integer
     */
    protected $population = 0;

    /**
     * What's the limit for the resources (for each resource separatly)?
     *
     * @var array
     */
    protected $resourcesCapacity = array();

    /**
     * How much resources does this town produces (for each resource separatly)?
     *
     * @var array
     */
    protected $resourcesProduction = array();

    /**
     * How much resources do we currently have available (for each resource separatly)?
     *
     * @var array
     */
    protected $resourcesAvailable = array();

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->townResources = new ArrayCollection();
        $this->townBuildings = new ArrayCollection();
        $this->townUnits = new ArrayCollection();
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
     * @return TownEntity
     */
    public function setPlanet(PlanetEntity $planet)
    {
        $this->planet = $planet;

        return $this;
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
     * @return TownEntity
     */
    public function setUser(UserEntity $user = null)
    {
        $this->user = $user;

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

    /**
     * @return TownEntity
     */
    public function prepareTownResources($amount = 0)
    {
        $resources = Resources::getAll();
        $townResources = array();
        $townResourcesCollection = $this->getTownResources();
        if (!empty($townResourcesCollection)) {
            foreach ($townResourcesCollection as $singleTownResouce) {
                $key = $singleTownResouce->getResource();
                $townResources[$key] = $singleTownResouce;
            }
        }

        foreach ($resources as $resource => $resourceName) {
            if (isset($townResources[$resource])) {
                $townResource = $townResources[$resource];
                $townResource
                    ->setAmount($amount)
                ;
            } else {
                $townResource = new TownResourceEntity();
                $townResource
                    ->setResource($resource)
                    ->setAmount($amount)
                ;

                $this->addTownResource($townResource);
            }
        }

        return $this;
    }

    /*** Resources production ***/
    /**
     * @return array
     */
    public function getResourcesProduction()
    {
        return $this->resourcesProduction;
    }

    /**
     * @return array $resourcesProduction
     */
    public function setResourcesProduction($resourcesProduction)
    {
        $this->resourcesProduction = $resourcesProduction;

        return $this;
    }

    /*** Resources available ***/
    /**
     * @return array
     */
    public function getResourcesAvailable()
    {
        return $this->resourcesAvailable;
    }

    /**
     * @return array $resourcesAvailable
     */
    public function setResourcesAvailable($resourcesAvailable)
    {
        $this->resourcesAvailable = $resourcesAvailable;

        return $this;
    }

    /*** Population capacity ***/
    /**
     * @return integer
     */
    public function getPopulationCapacity()
    {
        return $this->populationCapacity;
    }

    /**
     * @return integer $populationCapacity
     */
    public function setPopulationCapacity($populationCapacity)
    {
        $this->populationCapacity = $populationCapacity;

        return $this;
    }

    /*** Population ***/
    /**
     * @return integer
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * @return integer $population
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }

    /*** Resources capacity ***/
    /**
     * @return array
     */
    public function getResourcesCapacity()
    {
        return $this->resourcesCapacity;
    }

    /**
     * @return array $resourcesCapacity
     */
    public function setResourcesCapacity(array $resourcesCapacity = array())
    {
        $this->resourcesCapacity = $resourcesCapacity;

        return $this;
    }

    /*** Resources ***/
    /**
     * The combined version of resources:
     *   - available (currently)
     *   - capacity (total)
     *   - production (per minute)
     *
     * @return array
     */
    public function getResources()
    {
        $resources = array();
        $allResources = Resources::getAll();
        $resourcesProduction = $this->getResourcesProduction();
        $resourcesCapacity = $this->getResourcesCapacity();
        $resourcesAvailable = $this->getResourcesAvailable();

        foreach ($allResources as $resourceKey => $resourceName) {
            $resources[$resourceKey] = array(
                'available' => $resourcesAvailable[$resourceKey],
                'capacity' => $resourcesCapacity[$resourceKey],
                'production' => $resourcesProduction[$resourceKey],
            );
        }

        return $resources;
    }

    /*** Town Building ***/
    /**
     * @return TownBuildingEntity
     */
    public function getTownBuildings()
    {
        return $this->townBuildings->toArray();
    }

    /**
     * @param ArrayCollection $townBuildings
     *
     * @return TownEntity
     */
    public function setTownBuildings($townBuildings)
    {
        foreach ($townBuildings as $townBuilding) {
            $townBuilding->setTown($this);
        }

        $this->townBuildings = $townBuildings;

        return $this;
    }

    /**
     * @param TownBuildingEntity $townBuilding
     *
     * @return TownEntity
     */
    public function addTownBuilding(TownBuildingEntity $townBuilding)
    {
        if (!$this->townBuildings->contains($townBuilding)) {
            $townBuilding->setTown($this);
            $this->townBuildings->add($townBuilding);
        }

        return $this;
    }

    /**
     * @param TownBuildingEntity $townBuilding
     *
     * @return TownEntity
     */
    public function removeTownBuilding(TownBuildingEntity $townBuilding)
    {
        $townBuilding->setTown(null);
        $this->townBuildings->removeElement($townBuilding);

        return $this;
    }

    /*** Town Units ***/
    /**
     * @return ArrayCollection
     */
    public function getTownUnits()
    {
        return $this->townUnits->toArray();
    }

    /**
     * @param ArrayCollection $townUnits
     *
     * @return CountryEntity
     */
    public function setTownUnits($townUnits)
    {
        $this->townUnits = $townUnits;

        return $this;
    }

    /*** Coordinates ***/
    /**
     * @return string
     */
    public function getCoordinates()
    {
        $townBuildings = $this->getTownBuildings();

        foreach ($townBuildings as $townBuilding) {
            if ($townBuilding->getBuilding() === Buildings::CAPITOL) {
                return $townBuilding->getCoordinates();
            }
        }

        return false;
    }

    /**
     * @return integer
     */
    public function getCoordinatesX()
    {
        $townBuildings = $this->getTownBuildings();

        foreach ($townBuildings as $townBuilding) {
            if ($townBuilding->getBuilding() === Buildings::CAPITOL) {
                return $townBuilding->getCoordinatesX();
            }
        }

        return false;
    }

    /**
     * @return integer
     */
    public function getCoordinatesY()
    {
        $townBuildings = $this->getTownBuildings();

        foreach ($townBuildings as $townBuilding) {
            if ($townBuilding->getBuilding() === Buildings::CAPITOL) {
                return $townBuilding->getCoordinatesY();
            }
        }

        return false;
    }

    /*** Use Resource ***/
    /**
     * Yu MUST not forget to persist the entity after this call!
     *
     * @param array $resources
     *
     * @return TownEntity
     */
    public function useResources($resources)
    {
        $townResources = $this->getTownResources();

        if (!empty($resources)) {
            // Loop thought all the resources, that need to be substracted
            foreach ($resources as $resource => $amount) {
                // Go thought all the town resources, until you find the right one to substract it
                foreach ($townResources as $townResource) {
                    if ($townResource->getResource() == $resource) {
                        $townResourceAmount = $townResource->getAmount();
                        $townResource->setAmount($townResourceAmount - $amount);
                        break;
                    }
                }
            }
        }
    }

    /**
     * @return TownEntity
     */
    public function reloadData()
    {
        $resourcesProduction = array();
        $resourcesCapacity = array();
        $resourcesAvailable = array();
        $populationCapacity = 0;
        $population = 0;

        $allResources = Resources::getAll();
        $townBuildings = $this->getTownBuildings();
        $townResources = $this->getTownResources();

        foreach ($allResources as $resourceKey => $resourceName) {
            $resourcesProduction[$resourceKey] = 0;
            $resourcesCapacity[$resourceKey] = 0;
            $resourcesAvailable[$resourceKey] = 0;
        }

        if (!empty($townBuildings)) {
            foreach ($townBuildings as $townBuilding) {
                // Resources production
                $buildingResourcesProduction = $townBuilding->getResourcesProduction();
                if (!empty($buildingResourcesProduction)) {
                    foreach ($buildingResourcesProduction as $resource => $value) {
                        $resourcesProduction[$resource] += ($value / 100) * $townBuilding->getResourcesProductionPercentage();
                    }
                }

                // Storage capacity
                $buildingResourcesCapacity = $townBuilding->getResourcesCapacity();
                if (!empty($buildingResourcesCapacity)) {
                    if (is_numeric($buildingResourcesCapacity)) {
                        // Goes for ALL resources
                        foreach ($allResources as $resource => $resourceName) {
                            $resourcesCapacity[$resource] += $buildingResourcesCapacity;
                        }
                    } elseif (is_array($buildingResourcesCapacity)) {
                        // Only this defined resources
                        foreach ($buildingResourcesCapacity as $resource => $resourceAmount) {
                            $resourcesCapacity[$resource] += $resourceAmount;
                        }
                    }
                }

                // Population capacity
                $buildingPopulationCapacity = $townBuilding->getPopulationCapacity();
                if (!empty($buildingPopulationCapacity)) {
                    $populationCapacity += $buildingPopulationCapacity;
                }
            }
        }

        if (!empty($townResources)) {
            foreach ($townResources as $townResource) {
                $resourceKey = $townResource->getResource();
                $resourcesAvailable[$resourceKey] = $townResource->getAmount();
            }
        }

        // Money shouldn't depend on the storage, so just set the capacity to -1 ("unlimited" in other words)
        $resourcesCapacity[Resources::MONEY] = -1;

        $this->setResourcesProduction($resourcesProduction);
        $this->setResourcesCapacity($resourcesCapacity);
        $this->setResourcesAvailable($resourcesAvailable);
        $this->setPopulationCapacity($populationCapacity);
        $this->setPopulation($population);

        return $this;
    }

    /*** Time last updated resources ***/
    /**
     * @return \DateTime
     */
    public function getTimeLastUpdatedResources()
    {
        return $this->timeLastUpdatedResources;
    }

    /**
     * @param \DateTime $timeLastUpdatedResources
     *
     * @return TownEntity
     */
    public function setTimeLastUpdatedResources(\DateTime $timeLastUpdatedResources = null)
    {
        $this->timeLastUpdatedResources = $timeLastUpdatedResources;

        return $this;
    }

    /*** Buildings limit ***/
    /**
     * @return integer
     */
    public function getBuildingsLimit()
    {
        return 16; // To-Do: What will increase the limit?
    }

    /***** Coat of arms image url *****/
    /**
     * @return string
     */
    public function getCoatOfArmsImageUrl($baseUrl)
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
        return '/assets/images/towns/placeholder.png';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
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

        if (
            in_array('*', $fields) ||
            in_array('buildings_limit', $fields)
        ) {
            $data['buildings_limit'] = $this->getBuildingsLimit();
        }

        if (
            in_array('*', $fields) ||
            in_array('population', $fields)
        ) {
            $data['population'] = $this->getPopulation();
        }

        if (
            in_array('*', $fields) ||
            in_array('population_capacity', $fields)
        ) {
            $data['population_capacity'] = $this->getPopulationCapacity();
        }

        if (
            in_array('*', $fields) ||
            in_array('resources', $fields)
        ) {
            $data['resources'] = $this->getResources();
        }

        if (
            in_array('*', $fields) ||
            in_array('resources_production', $fields)
        ) {
            $data['resources_production'] = $this->getResourcesProduction();
        }

        if (
            in_array('*', $fields) ||
            in_array('resources_available', $fields)
        ) {
            $data['resources_available'] = $this->getResourcesAvailable();
        }

        if (
            in_array('*', $fields) ||
            in_array('resources_capacity', $fields)
        ) {
            $data['resources_capacity'] = $this->getResourcesCapacity();
        }

        if (
            in_array('*', $fields) ||
            in_array('town_buildings', $fields) ||
            Helper::strpos_array($fields, 'town_buildings.') !== false
        ) {
            $townBuildings = array();
            $townBuildingsCollection = $this->getTownBuildings();

            if (!empty($townBuildingsCollection)) {
                $townBuildingFields = array('*');

                if ($index = Helper::strpos_array($fields, 'town_buildings.')) {
                    $field = $fields[$index];
                    $townBuildingFields = explode(
                        ',',
                        trim(
                            str_replace(
                                'town_buildings.',
                                '',
                                $field
                            ),
                            '{}'
                        )
                    );
                }

                foreach ($townBuildingsCollection as $townBuilding) {
                    $townBuildings[] = $townBuilding->toArray($townBuildingFields);
                }
            }

            $data['town_buildings'] = $townBuildings;
        }

        if (
            in_array('*', $fields) ||
            in_array('time_created', $fields)
        ) {
            $data['time_created'] = $this->getTimeCreated()->format(DATE_ATOM);
        }

        if (
            in_array('*', $fields) ||
            in_array('country', $fields) ||
            Helper::strpos_array($fields, 'country.') !== false
        ) {
            $countryFields = array('*');

            if ($index = Helper::strpos_array($fields, 'country.')) {
                $field = $fields[$index];
                $countryFields = explode(
                    ',',
                    trim(
                        str_replace(
                            'country.',
                            '',
                            $field
                        ),
                        '{}'
                    )
                );
            }

            $data['country'] = $this->getCountry()->toArray($countryFields);
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

        return $data;
    }

    /**
     * @ORM\PostLoad
     */
    public function postLoad()
    {
        $this->reloadData();
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist()
    {
        $this->reloadData();
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
