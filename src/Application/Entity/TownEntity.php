<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Game\Resources;
use Application\Game\Buildings;
use Application\Game\Building\Building;

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
     * @ORM\OneToMany(targetEntity="Application\Entity\TownBuildingEntity", mappedBy="town", cascade={"all"}, orphanRemoval=true)
     */
    protected $townBuildings;

    /**
     * How much is the limit for population?
     *
     * @var integer
     */
    protected $populationCapacity;
    
    /**
     * How much is the current population of that town?
     *
     * @var integer
     */
    protected $population;

    /**
     * What's the limit for the resources?
     *
     * @var array
     */
    protected $storageCapacity;

    /**
     * How much resources does this town produces?
     *
     * @var array
     */
    protected $resourcesProduction;

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

    /*** Storage capacity ***/
    /**
     * @return integer
     */
    public function getStorageCapacity()
    {
        return $this->storageCapacity;
    }

    /**
     * @return integer $storageCapacity
     */
    public function setStorageCapacity(array $storageCapacity = array())
    {
        $this->storageCapacity = $storageCapacity;

        return $this;
    }

    /*** Resources ***/
    /**
     * The combiened version of resources:
     *   - amount
     *   - storage
     *   - production
     *
     * @return array
     */
    public function getResources()
    {
        $resources = array();
        $allResources = Resources::getAll();
        $resourcesProduction = $this->getResourcesProduction();
        $storageCapacity = $this->getStorageCapacity();
        $townResources = $this->getTownResources();

        foreach ($allResources as $resourceKey => $resourceName) {
            $resources[$resourceKey] = array(
                'amount' => 0,
                'storage' => $storageCapacity[$resourceKey],
                'production' => $resourcesProduction[$resourceKey],
            );
        }

        foreach ($townResources as $townResource) {
            $resourceKey = $townResource->getResource();
            $resources[$resourceKey]['amount'] = $townResource->getAmount();
        }

        return $resources;
    }

    /*** Town Building ***/
    /**
     * @return TownBuildingEntity
     */
    public function getTownBuildings()
    {
        return $this->townBuildings;
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

    /**
     * @return void
     */
    public function initialize()
    {
        $resourcesProduction = array();
        $storageCapacity = array();
        $populationCapacity = 0;
        $population = 0;
        $allResources = Resources::getAll();
        $townBuildings = $this->getTownBuildings();

        foreach ($allResources as $resourceKey => $resourceName) {
            $resourcesProduction[$resourceKey] = 0;
            $storageCapacity[$resourceKey] = 0;
        }

        if (!empty($townBuildings)) {
            foreach ($townBuildings as $townBuilding) {
                $className = 'Application\\Game\\Building\\'.Buildings::getClassName($townBuilding->getBuilding());
                $level = $townBuilding->getLevel();
                $building = new $className();

                // Resources production
                $buildingResourcesProduction = $building->getResourcesProduction();
                if (
                    !empty($buildingResourcesProduction) &&
                    isset($buildingResourcesProduction[$level])
                ) {
                    $buildingResourcesProduction = $buildingResourcesProduction[$level];

                    foreach ($buildingResourcesProduction as $resource => $value) {
                        $resourcesProduction[$resource] += $value;
                    }
                }

                // Storage capacity
                $buildingStorageCapacity = $building->getStorageCapacity();
                if (
                    !empty($buildingStorageCapacity) &&
                    isset($buildingStorageCapacity[$level])
                ) {
                    $buildingStorageCapacity = $buildingStorageCapacity[$level];

                    foreach ($allResources as $resource => $resourceName) {
                        $storageCapacity[$resource] += $buildingStorageCapacity;
                    }
                }

                // Ppulation capacity
                $buildingPopulationCapacity = $building->getPopulationCapacity();
                if (
                    !empty($buildingPopulationCapacity) &&
                    isset($buildingPopulationCapacity[$level])
                ) {
                    $populationCapacity += $buildingPopulationCapacity[$level];
                }
            }
        }

        $this->setResourcesProduction($resourcesProduction);
        $this->setStorageCapacity($storageCapacity);
        $this->setPopulationCapacity($populationCapacity);
        $this->setPopulation($population);
    }

    /**
     * @ORM\PostLoad
     */
    public function postLoad()
    {
        $this->initialize();
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
