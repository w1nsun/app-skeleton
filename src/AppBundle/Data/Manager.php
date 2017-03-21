<?php

namespace AppBundle\Data;

use MongoDB\BSON\ObjectID;
use MongoDB\Driver\BulkWrite;
use \MongoDB\Driver\Manager as MongoDbManager;
use MongoDB\Driver\Query;

abstract class Manager
{
    /**
     * @var MongoDbManager
     */
    protected $dbManager;

    /**
     * @var string
     */
    protected $dbName;

    /**
     * @param string $dbName
     * @param MongoDbManager $dbManager
     */
    public function __construct(string $dbName, MongoDbManager $dbManager)
    {
        $this->dbName = $dbName;
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
        return $this->dbManager->executeBulkWrite($this->getNamespace(), $bulk);
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

    /**
     * @param $id
     * @return null
     */
    public function findById($id)
    {
        $id = $id instanceof ObjectID ? $id : new ObjectID($id);
        $query = new Query(['_id' => $id], ['limit' => 1]);
        $rows = $this->dbManager->executeQuery($this->getNamespace(), $query);
        foreach ($rows as $row) {
            return $row;
        }

        return null;
    }

    /**
     * @param $query
     * @return null
     */
    public function findOne($query)
    {
        $query = new Query($query, ['limit' => 1]);
        $rows = $this->dbManager->executeQuery($this->getNamespace(), $query);
        foreach ($rows as $row) {
            return $row;
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        return $this->dbName . '.' . $this->getCollectionName();
    }
}