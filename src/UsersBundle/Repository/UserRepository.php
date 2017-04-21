<?php

namespace UsersBundle\Repository;

use UsersBundle\Entity\User;
use UsersBundle\Storage\UserMongoStorage;

class UserRepository
{
    /**
     * @var UserMongoStorage
     */
    private $storage;

    /**
     * @param UserMongoStorage $storage
     */
    public function __construct(UserMongoStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $document = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'is_active' => $user->isActive(),
            'created_at' => $user->getCreatedAt(),
            'roles' => $user->getRoles(),
        ];

        if (null === $user->getId()) {
            $id = $this->storage->add($document);
            $user->setId((string) $id);

            return;
        }

        $this->storage->update($user->getId(), $document);
    }

    /**
     * @param $username
     * @return null|User
     */
    public function findByUsername($username): ?User
    {
        $rows = $this->storage->find(['username' => $username])->toArray();
        if (!count($rows)) {
            return null;
        }

        return User::fromState((array) $rows[0]);
    }

    /**
     * @param int $skip
     * @param int $limit
     * @return array|User[]
     */
    public function findAll(int $skip = 0, int $limit = 25): array
    {
        $rows = $this->storage->find([], $skip, $limit)->toArray();
        $users = [];
        foreach ($rows as $row) {
            $users[] = User::fromState((array) $row);
        }

        return $users;
    }
}