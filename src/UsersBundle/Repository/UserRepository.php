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
}