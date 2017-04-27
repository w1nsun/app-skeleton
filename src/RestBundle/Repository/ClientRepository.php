<?php

namespace RestBundle\Repository;

use RestBundle\Entity\Client;
use RestBundle\Storage\ClientMongoStorage;

class ClientRepository
{
    /**
     * @var ClientMongoStorage
     */
    private $storage;

    /**
     * @param ClientMongoStorage $storage
     */
    public function __construct(ClientMongoStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param Client $client
     */
    public function save(Client $client)
    {
        $document = [
            'name' => $client->getName(),
            'token' => $client->getToken(),
            'resources' => $client->getResources(),
            'roles' => $client->getRoles(),
            'created_at' => $client->getCreatedAt(),
            'is_active' => $client->isActive(),
        ];

        if (null === $client->getId()) {
            $id = $this->storage->add($document);
            $client->setId((string) $id);

            return;
        }

        $this->storage->update($client->getId(), $document);
    }

    /**
     * @param int $skip
     * @param int $limit
     * @return array|Client[]
     */
    public function findAll($skip = 0, $limit = 25)
    {
        $rows = $this->storage->find([], $skip, $limit)->toArray();
        $users = [];
        foreach ($rows as $row) {
            $users[] = Client::fromState((array) $row);
        }

        return $users;
    }
}
