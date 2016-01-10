<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item Resource Entity
 *
 * @ORM\Table(name="item_resources", uniqueConstraints={@ORM\UniqueConstraint(columns={"item_id", "resource_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\ItemResourceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ItemResourceEntity extends AbstractBasicEntity
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
     * @ORM\ManyToOne(targetEntity="Application\Entity\ItemEntity", inversedBy="itemResources")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    protected $item;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\ResourceEntity", inversedBy="itemResources")
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
     * @return ItemResourceEntity
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /*** Item ***/
    /**
     * @return ItemEntity
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param ItemEntity $item
     *
     * @return ItemResourceEntity
     */
    public function setItem(ItemEntity $item = null)
    {
        $this->item = $item;

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
