<?php

namespace Application\Game\Resource;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class AbstractResource implements ResourceInterface
{
    /**
     * What's the name of that resource?
     * Example:
     *   'Iron ore'
     *
     * @var string
     */
    protected $name;

    /**
     * What's the key of that building?
     * Example:
     *   'iron_ore'
     *
     * @var string
     */
    protected $key;

    /**
     * What's the slug of that building?
     * Example:
     *   'iron-ore'
     *
     * @var string
     */
    protected $slug;

    /**
     * What's the description of that resource?
     * Example:
     *   'Used for iron stuff'
     *
     * @var string
     */
    protected $description;

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
}
