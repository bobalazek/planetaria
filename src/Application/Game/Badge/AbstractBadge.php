<?php

namespace Application\Game\Badge;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class AbstractBadge implements BadgeInterface
{
    /**
     * What's the name of that badge?
     * Example:
     *   'Beginner'
     *
     * @var string
     */
    protected $name;

    /**
     * What's the key of that badge?
     * Example:
     *   'beginner'
     *
     * @var string
     */
    protected $key;

    /**
     * What's the slug of that badge?
     * Example:
     *   'beginner'
     *
     * @var string
     */
    protected $slug;

    /**
     * What's the description of that badge?
     * Example:
     *   'You are great!'
     *
     * @var string
     */
    protected $description;

    /**
     * How much experience points does the user need, to earn this badge?
     *   100
     * or
     *   -1 (for infinitive; means, you can get this medal only on custom events)
     *
     * @var integer
     */
    protected $minimumExperiencePoints = -1;

    /***** Name *****/
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /***** Key *****/
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /***** Slug *****/
    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /***** Description *****/
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /***** Minumum experience points *****/
    /**
     * @return integer
     */
    public function getMinimumExperiencePoints()
    {
        return $this->minimumExperiencePoints;
    }

    /**
     * @param integer $minimumExperiencePoints
     */
    public function setMinimumExperiencePoints($minimumExperiencePoints)
    {
        $this->minimumExperiencePoints = $minimumExperiencePoints;

        return $this;
    }
}
