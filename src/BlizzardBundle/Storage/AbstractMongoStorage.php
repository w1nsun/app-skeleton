<?php

namespace BlizzardBundle\Storage;

use MongoDB\BSON\ObjectID;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\WriteConcern;

abstract class AbstractMongoStorage
{
    /**
     * @var Manager
     */
    protected $mongodbManager;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param Manager $mongodbManager
     * @param array $options
     */
    public function __construct(Manager $mongodbManager, array $options)
    {
        if (!isset($options['database'])) {
            throw new \InvalidArgumentException('The "database" option is mandatory');
        }

        $this->mongodbManager = $mongodbManager;
        $this->options = $options;
    }

    abstract protected function getCollectionName(): string;

    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        return $this->options['database'].'.'.$this->getCollectionName();
    }

    /**
     * @param array $document
     * @return ObjectID
     */
    public function add(array $document)
    {
        $bulk = new BulkWrite();
        $bulk->insert($document);
        $result = $this->flush($bulk);

        foreach ($result->getUpsertedIds() as $index => $id) {
            return $id;
        }

        return null;
    }

    /**
     * @param string $id
     * @param array $document
     * @return void
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
    protected function flush(BulkWrite $bulk)
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);

        return $this->mongodbManager->executeBulkWrite($this->getNamespace(), $bulk, $writeConcern);
    }

    /**
     * @param $id
     * @return null
     */
    public function find($id)
    {
        $id = $id instanceof ObjectID ? $id : new ObjectID($id);
        $query = new Query(['_id' => $id], ['limit' => 1]);
        $rows = $this->mongodbManager->executeQuery($this->getNamespace(), $query);
        foreach ($rows as $row) {
            return $row;
        }

        return null;
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $bulk = new BulkWrite();
        $bulk->delete(
            [
                '_id' => new ObjectID($id)
            ]
        );
        $this->flush($bulk);
    }

}