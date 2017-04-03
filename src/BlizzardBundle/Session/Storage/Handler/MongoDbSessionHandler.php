<?php
namespace BlizzardBundle\Session\Storage\Handler;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager as MongoDbManager;

class MongoDbSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var MongoDbManager
     */
    private $mongo;

    /**
     * @var array
     */
    private $options;

    /**
     * @param MongoDbManager $mongo
     * @param array $options
     */
    public function __construct(MongoDbManager $mongo, array $options)
    {
        if (!isset($options['database']) || !isset($options['collection'])) {
            throw new \InvalidArgumentException(
                'You must provide the "database" and "collection" option for MongoDBSessionHandler'
            );
        }

        $this->mongo = $mongo;
        $this->options = array_merge([
            'id_field' => '_id',
            'data_field' => 'data',
            'time_field' => 'time',
            'expiry_field' => 'expires_at',
        ], $options);
    }

    public function open($savePath, $sessionName)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    protected function getNamespace()
    {
        return $this->options['database'] .'.'. $this->options['collection'];
    }

    public function destroy($sessionId)
    {
        $bulk = new BulkWrite();
        $bulk->delete([
            $this->options['id_field'] => $sessionId
        ]);
        $this->mongo->executeBulkWrite($this->getNamespace(), $bulk);

        return true;
    }

    public function gc($maxlifetime)
    {
        $bulk = new BulkWrite();
        $bulk->delete([
            $this->options['expiry_field'] => array('$lt' => $this->createDateTime()),
        ]);
        $this->mongo->executeBulkWrite($this->getNamespace(), $bulk);

        return true;
    }

    public function write($sessionId, $data)
    {
        $bulk = new BulkWrite();
        $bulk->update(
            [
                $this->options['id_field'] => $sessionId,
            ],
            [
                '$set' => [
                    $this->options['time_field'] => $this->createDateTime(),
                    $this->options['expiry_field'] => $this->createDateTime(time() + (int) ini_get('session.gc_maxlifetime')),
                    $this->options['data_field'] => new \MongoDB\BSON\Binary($data, \MongoDB\BSON\Binary::TYPE_OLD_BINARY),
                ],
            ],
            [
                'upsert' => true,
            ]
        );

        $this->mongo->executeBulkWrite($this->getNamespace(), $bulk);

        return true;
    }


    public function read($sessionId)
    {
        $condition = [
            $this->options['id_field'] => $sessionId,
            $this->options['expiry_field'] => [
                '$gte' => $this->createDateTime()
            ],
        ];
        $query = new \MongoDB\Driver\Query($condition);
        $results = $this->mongo->executeQuery($this->getNamespace(), $query)->toArray();

        if (!count($results)) {
            return '';
        }

        $dbData = (array) array_shift($results);

        if ($dbData[$this->options['data_field']] instanceof \MongoDB\BSON\Binary) {
            return $dbData[$this->options['data_field']]->getData();
        }

        return $dbData[$this->options['data_field']]->bin;
    }


    private function createDateTime($seconds = null)
    {
        if (null === $seconds) {
            $seconds = time();
        }

        return new \MongoDB\BSON\UTCDateTime($seconds * 1000);
    }
}