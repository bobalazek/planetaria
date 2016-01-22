<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Town Resource Entity
 *
 * @ORM\Table(name="town_resources", uniqueConstraints={@ORM\UniqueConstraint(columns={"town_id", "resource"})})
 * @ORM\Entity(repositoryClass="Application\Repository\TownResourceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownResourceEntity extends AbstractBasicEntity
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
     * @ORM\Column(name="resource", type="string", length=32)
     */
    protected $resource;

    /**
     * How much of that resource do we have in the storage?
     *
     * @var float
     *
     * @ORM\Column(name="amount", type="decimal", scale=3)
     */
    protected $amount = 0;

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
     * @ORM\ManyToOne(targetEntity="Application\Entity\TownEntity", inversedBy="townResources")
     * @ORM\JoinColumn(name="town_id", referencedColumnName="id")
     */
    protected $town;

    /*** Resource ***/
    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param string $resource
     *
     * @return TownResourceEntity
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /*** Amount ***/
    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return TownResourceEntity
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /*** Town ***/
    /**
     * @return TownEntity
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param TownEntity $town
     *
     * @return TownResourceEntity
     */
    public function setTown(TownEntity $town = null)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getResource();
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
