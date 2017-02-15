<?php

namespace AppBundle\Entity;

use MongoDB\BSON\ObjectID;
use MongoDB\Driver\BulkWrite;
use \MongoDB\Driver\Manager as MongoDbManager;

abstract class Manager
{
    /**
     * @var MongoDbManager
     */
    protected $dbManager;

    /**
     * @var BulkWrite
     */
    private $bulk;

    /**
     * @param MongoDbManager $dbManager
     */
    public function __construct(MongoDbManager $dbManager)
    {
        $this->dbManager = $dbManager;
    }

    /**
     * @return string
     */
    abstract public function getCollectionName();

    /**
     * @param array $document
     * @return ObjectID
     */
    public function insert(array $document)
    {
        $this->bulk = null;
        $id = $this->getBulk()->insert($document);
        $this->flush();

        return $id;
    }

    /**
     * @return BulkWrite
     */
    private function getBulk()
    {
        if (null === $this->bulk) {
            $this->bulk = new BulkWrite();
        }

        return $this->bulk;
    }


    private function flush()
    {
        return $this->dbManager->executeBulkWrite('db.' . $this->getCollectionName(), $this->getBulk());
    }
}