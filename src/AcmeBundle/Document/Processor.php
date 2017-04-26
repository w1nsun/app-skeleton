<?php

namespace AcmeBundle\Document;

use Tequila\MongoDB\ODM\Document;

class Processor extends Document
{
    private $speed;
    private $type;

    public function bsonSerialize()
    {
        return [
            'speed' => $this->speed,
            'type' => $this->type,
        ];
    }

    public function bsonUnserialize(array $data)
    {
        $this->type = $data['type'];
        $this->speed = $data['speed'];
    }

    /**
     * @return mixed
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param mixed $speed
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}