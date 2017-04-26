<?php

namespace AcmeBundle\Document;

use MongoDB\BSON\UTCDateTime;
use Tequila\MongoDB\ODM\Document;
use Tequila\MongoDB\ODM\DocumentMetadata;

class Computer extends Document
{
    private $powerSupply;
    private $gpu;
    private $cpu;
    private $createdAt;

    public function bsonSerialize()
    {
        return array_merge(parent::bsonSerialize(), [
            'power_supply' => $this->powerSupply,
            'gpu' => $this->gpu,
            'cpu' => $this->cpu,
            'created_at' => $this->createdAt instanceof \DateTime ? new UTCDateTime($this->createdAt) : null,
        ]);
    }

    public function bsonUnserialize(array $data)
    {
        parent::bsonUnserialize($data);

        $this->powerSupply = $data['power_supply'];
        $this->gpu = $data['gpu'];
        $this->cpu = $data['cpu'];
        $this->createdAt = $data['created_at'] instanceof UTCDateTime ? $data['created_at']->toDateTime() : $data['created_at'];
    }

    public static function getMetadata()
    {
        return new DocumentMetadata('computer');
    }

    /**
     * @return mixed
     */
    public function getPowerSupply()
    {
        return $this->powerSupply;
    }

    /**
     * @param mixed $powerSupply
     */
    public function setPowerSupply($powerSupply)
    {
        $this->powerSupply = $powerSupply;
    }

    /**
     * @return Processor
     */
    public function getGpu()
    {
        return $this->gpu;
    }

    /**
     * @param mixed $gpu
     */
    public function setGpu($gpu)
    {
        $this->gpu = $gpu;
    }

    /**
     * @return mixed
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * @param Processor $cpu
     */
    public function setCpu(Processor $cpu)
    {
        $this->cpu = $cpu;
        $this->set('cpu', $this->cpu);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
