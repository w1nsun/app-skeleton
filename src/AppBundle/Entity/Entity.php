<?php

namespace AppBundle\Entity;

use MongoDB\BSON\Serializable;
use MongoDB\BSON\Unserializable;

abstract class Entity implements Serializable, Unserializable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return null === $this->id;
    }

    /**
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->populateProperties($properties);
    }

    /**
     * @param array $properties
     */
    protected function populateProperties(array $properties)
    {
        foreach ($properties as $name => $value) {
            $method = 'set' . ucwords($name);

            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }
}