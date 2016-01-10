<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * District Resource Storage Entity
 *
 * How many natural resource the district has?
 *
 * @ORM\Table(name="district_resources", uniqueConstraints={@ORM\UniqueConstraint(columns={"district_id", "resource_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\DistrictResourceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class DistrictResourceEntity extends AbstractBasicEntity
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
     * How much total resource this district has?
     *
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    protected $amount = 50000;

    /**
     * How much is left?
     *
     * @var integer
     *
     * @ORM\Column(name="amount_left", type="integer")
     */
    protected $amountLeft = 50000;

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
     * @ORM\ManyToOne(targetEntity="Application\Entity\DistrictEntity", inversedBy="districtResources")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     */
    protected $district;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\ResourceEntity", inversedBy="districtResources")
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
     * @return DistrictResourceEntity
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /*** Amount left ***/
    /**
     * @return integer
     */
    public function getAmountLeft()
    {
        return $this->amountLeft;
    }

    /**
     * @param integer $amountLeft
     *
     * @return DistrictResourceEntity
     */
    public function setAmountLeft($amountLeft)
    {
        $this->amountLeft = $amountLeft;

        return $this;
    }

    /*** District ***/
    /**
     * @return DistrictEntity
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param DistrictEntity $district
     *
     * @return DistrictResourceEntity
     */
    public function setDistrict(DistrictEntity $district = null)
    {
        $this->district = $district;

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
     * @return DistrictResourceEntity
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
