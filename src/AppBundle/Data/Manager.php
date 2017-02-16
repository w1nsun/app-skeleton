<?php

namespace AppBundle\Data;

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
        $bulk = new BulkWrite();
        $id = $bulk->insert($document);
        $this->flush($bulk);

        return $id;
    }

    /**
     * @param string $id
     * @param array $document
     * @return string|void
     */
    public function update(string $id, array $document)
    {
        $bulk = new BulkWrite();
        $bulk->update(
            [
                '_id' => new ObjectID($id)
            ],
            [
                '$set' => $document
            ]
        );
        $this->flush($bulk);
    }

    /**
     * @param BulkWrite $bulk
     * @return \MongoDB\Driver\WriteResult
     */
    private function flush(BulkWrite $bulk)
    {
        return $this->dbManager->executeBulkWrite('db.' . $this->getCollectionName(), $bulk);
    }

    /**
     * @param Entity $entity
     */
    public function save(Entity $entity)
    {
        if ($entity->isNew()) {
            $id = $this->insert($entity->bsonSerialize());
            $entity->setId($id);

            return;
        }

        $this->update($entity->getId(), $entity->bsonSerialize());
    }
}