<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Unit Resource Entity
 *
 * @ORM\Table(name="unit_resources", uniqueConstraints={@ORM\UniqueConstraint(columns={"unit_id", "resource_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\UnitResourceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UnitResourceEntity extends AbstractBasicEntity
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
     * How much of that resource do we have in the storage?
     *
     * @var integer
     *
     * @ORM\Column(name="health_points", type="integer")
     */
    protected $amount;

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
     * @ORM\ManyToOne(targetEntity="Application\Entity\UnitEntity", inversedBy="unitResources")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     */
    protected $unit;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\ResourceEntity", inversedBy="unitResources")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     */
    protected $resource;

    /*** Amount ***/
    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param integer $amount
     *
     * @return UnitResourceEntity
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /*** Unit ***/
    /**
     * @return UnitEntity
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param UnitEntity $unit
     *
     * @return UnitResourceEntity
     */
    public function setUnit(UnitEntity $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /*** Resource ***/
    /**
     * @return ResourceEntity
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param ResourceEntity $resource
     *
     * @return BuildingResourceEntity
     */
    public function setResource(ResourceEntity $resource)
    {
        $this->resource = $resource;

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
