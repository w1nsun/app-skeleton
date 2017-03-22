<?php

namespace BlizzardBundle\Repository;

use MongoDB\BSON\ObjectID;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\WriteConcern;

abstract class AbstractMongoRepository
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
        if (!isset($options[$this->getConnectionName()]['database'])) {
            throw new \InvalidArgumentException('The "database" option is mandatory');
        }

        $this->mongodbManager = $mongodbManager;
        $this->options = $options;
    }

    /**
     * @return string
     */
    abstract public function getCollectionName(): string;

    /**
     * @return string
     */
    protected function getConnectionName(): string
    {
        return 'default';
    }

    /**
     * @param array $document
     * @return ObjectID
     */
    protected function insert(array $document)
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
    protected function update(string $id, array $document)
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
     * @return string
     */
    protected function getNamespace(): string
    {
        return $this->options[$this->getConnectionName()]['database'].'.'.$this->getCollectionName();
    }
}