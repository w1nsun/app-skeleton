<?php

namespace BlizzardBundle\Entity;

use BlizzardBundle\Exception\PropertyNotFoundException;
use BlizzardBundle\Interfaces\EntityInterface;
use BlizzardBundle\Interfaces\IdInterface;

abstract class AbstractEntity implements EntityInterface, \Serializable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        foreach ($properties as $name => $value) {
            $method = 'set' . ucwords($name);

            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (property_exists($this, $name)) {
                $this->$name = $value;
            } else {
                throw new PropertyNotFoundException(sprintf('Property "%s" not declared', $name));
            }
        }
    }

    /**
     * @param IdInterface $id
     */
    public function setId(IdInterface $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}