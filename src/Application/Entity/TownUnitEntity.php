<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Town Unit Entity
 *
 * @ORM\Table(name="town_units")
 * @ORM\Entity(repositoryClass="Application\Repository\TownUnitRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class TownUnitEntity extends AbstractBasicEntity
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
     * @ORM\Column(name="unit", type="string", length=32)
     */
    protected $unit;

    /**
     * At which level is the unit?
     *
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    protected $level = 0;

    /**
     * How much of that units do we have?
     *
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
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
     * @ORM\ManyToOne(targetEntity="Application\Entity\TownEntity", inversedBy="townUnits")
     * @ORM\JoinColumn(name="town_id", referencedColumnName="id")
     */
    protected $town;

    /*** Unit ***/
    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     *
     * @return TownUnitEntity
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

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
     * @return TownUnitEntity
     */
    public function setTown(TownEntity $town)
    {
        $this->town = $town;

        return $this;
    }

    /*** Level ***/
    /**
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param integer $level
     *
     * @return TownUnitEntity
     */
    public function setLevel($level)
    {
        $this->level = $level;

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
     * @return TownUnitEntity
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

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
