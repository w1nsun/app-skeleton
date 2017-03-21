<?php

namespace BlizzardBundle\Repository;

use MongoDB\Driver\Manager;

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
     * @return string
     */
    protected function getConnectionString(): string
    {
        if (!isset($this->options[$this->getConnectionName()]['host'])) {
            throw new \InvalidArgumentException('The "host" option is mandatory');
        }

        $connection = 'mongodb://';

        if (
            isset($this->options[$this->getConnectionName()]['username']) &&
            isset($this->options[$this->getConnectionName()]['password'])
        ) {
            $connection .= $this->options[$this->getConnectionName()]['username'].':'.
                $this->options[$this->getConnectionName()]['password'].'@';
        }

        return $connection.$this->options[$this->getConnectionName()]['host'];
    }
}