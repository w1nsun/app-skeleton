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

    public function save(User $user)
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
}