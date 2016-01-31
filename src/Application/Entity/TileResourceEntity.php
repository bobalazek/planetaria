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

    /*** Amount percentage ***/
    /**
     * @return integer
     */
    public function getAmountPercentage()
    {
        $total = $this->getAmount();
        $left = $this->getAmountLeft();

        if ($total == 0) {
            return 100;
        } elseif ($left == 0) {
            return 0;
        }

        return ceil(($left / $total) * 100);
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
            in_array('resource', $fields)
        ) {
            $data['resource'] = $this->getResource();
        }
        
        if (
            in_array('*', $fields) ||
            in_array('amount', $fields)
        ) {
            $data['amount'] = $this->getAmount();
        }
        
        if (
            in_array('*', $fields) ||
            in_array('amount_left', $fields)
        ) {
            $data['amount_left'] = $this->getAmountLeft();
        }

        return $data;
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
