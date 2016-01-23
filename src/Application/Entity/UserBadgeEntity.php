<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Game\Badges;

/**
 * User Badge Entity
 *
 * @ORM\Table(name="user_badges")
 * @ORM\Entity(repositoryClass="Application\Repository\UserBadgeRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserBadgeEntity extends AbstractBasicEntity
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
     * @ORM\Column(name="badge", type="string", length=64)
     */
    protected $badge;

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
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserEntity", inversedBy="userBadges")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /*** Badge ***/
    /**
     * @return string
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * @param BadgeEntity $badge
     *
     * @return UserBadgeEntity
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /*** Badge object ***/
    /**
     * @return AbstractBadge
     */
    public function getBadgeObject()
    {
        $className = 'Application\\Game\\Badge\\'.
            Badges::getClassName($this->getBadge())
        ;

        return new $className();
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
     * @return UserBadgeEntity
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;

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
