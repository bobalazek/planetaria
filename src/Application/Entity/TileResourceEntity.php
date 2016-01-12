<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tile Resource Entity
 *
 * @ORM\Table(name="tile_resources", uniqueConstraints={@ORM\UniqueConstraint(columns={"tile_id", "resource"})})
 * @ORM\Entity(repositoryClass="Application\Repository\TileResourceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TileResourceEntity extends AbstractBasicEntity
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
     * How much is the total amount of that resource in this tile?
     *
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    protected $amount = 10000;

    /**
     * How much is that resource left?
     *
     * @var integer
     *
     * @ORM\Column(name="amount_left", type="integer")
     */
    protected $amountLeft = 10000;

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
     * @ORM\ManyToOne(targetEntity="Application\Entity\TileEntity", inversedBy="tileResources")
     * @ORM\JoinColumn(name="tile_id", referencedColumnName="id")
     */
    protected $tile;

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
     * @return TileResourceEntity
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

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
     * @return TileResourceEntity
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
     * @param integer $amount
     *
     * @return TileResourceEntity
     */
    public function setAmountLeft($amountLeft)
    {
        $this->amountLeft = $amountLeft;

        return $this;
    }

    /*** Tile ***/
    /**
     * @return TileEntity
     */
    public function getTile()
    {
        return $this->tile;
    }

    /**
     * @param TileEntity $tile
     *
     * @return TileResourceEntity
     */
    public function setTile(TileEntity $tile = null)
    {
        $this->tile = $tile;

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
