<?php

namespace AppBundle\Data;

use MongoDB\BSON\Serializable;
use MongoDB\BSON\Unserializable;

abstract class Entity extends Model implements Serializable, Unserializable
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

    /**
     * @param string $name
     * @param array $arguments
     * @return null
     */
    public function __call(string $name, array $arguments)
    {
        $parsed_name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));

        if (strpos($name, 'get') === 0) {
            $name = str_replace('get_', '', $parsed_name);

            if (property_exists($this, $name)) {
                return $this->$name;
            }

            throw new \BadMethodCallException(sprintf('Unknown property "%s"', $name));
        }

        if (strpos($name, 'set') === 0) {
            $name = str_replace('set_', '', $parsed_name);

            if (property_exists($this, $name)) {
                $this->$name = $arguments[0];

                return null;
            }

            throw new \BadMethodCallException(sprintf('Unknown property "%s"', $name));
        }

        throw new \BadMethodCallException(sprintf('Method "%s" not found', $name));
    }
}