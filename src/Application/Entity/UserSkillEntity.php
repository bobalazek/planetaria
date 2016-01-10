<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User Skill Entity
 *
 * @ORM\Table(name="user_skills")
 * @ORM\Entity(repositoryClass="Application\Repository\UserSkillRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserSkillEntity extends AbstractBasicEntity
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
     * @var array
     *
     * @ORM\Column(name="points", type="integer")
     */
    protected $points = 0;

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
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserEntity", inversedBy="userSkills")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\SkillEntity", inversedBy="userSkills")
     * @ORM\JoinColumn(name="skill_id", referencedColumnName="id")
     */
    protected $skill;

    /*** Points ***/
    /**
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param integer $points
     *
     * @return UserSkillEntity
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(
            $role,
            $this->getRoles()
        );
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
     * @return UserSkillEntity
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;

        return $this;
    }

    /*** Skill ***/
    /**
     * @return SkillEntity
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * @param SkillEntity $skill
     *
     * @return UserSkillEntity
     */
    public function setSkill(SkillEntity $skill)
    {
        $this->skill = $skill;

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
