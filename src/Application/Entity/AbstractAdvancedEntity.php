<?php

namespace Application\Entity;

use Cocur\Slugify\Slugify;

/**
 * Abstract advanced entity
 *
 * Some of the advanced variables and methods (name, slug and description + id, timeCreated and timeUpdated from basic)
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class AbstractAdvancedEntity extends AbstractBasicEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $description;

    /*** Name ***/
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return AbstractAdvancedEntity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /*** Slug ***/
    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return AbstractAdvancedEntity
     */
    public function setSlug($slug)
    {
        $slugify = new Slugify();

        $this->slug = $slugify->slugify($slug);

        return $this;
    }

    /*** Description ***/
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return AbstractAdvancedEntity
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns data in array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'description' => $this->getDescription(),
            'time_created' => $this->getTimeCreated()->format(DATE_ATOM),
            'time_updated' => $this->getTimeUpdated()->format(DATE_ATOM),
        );
    }

    /**
     * Returns the string of that object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
