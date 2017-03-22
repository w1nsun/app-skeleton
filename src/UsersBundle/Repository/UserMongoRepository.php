<?php

namespace UsersBundle\Repository;

use BlizzardBundle\Repository\AbstractMongoRepository;
use UsersBundle\Entity\User;

class UserMongoRepository extends AbstractMongoRepository
{
    public function getCollectionName(): string
    {
        return 'users';
    }

    public function save(User $user)
    {
        if (null === $user->getId()) {
            $id = $this->insert($user->serialize());
            $user->setId((string) $id);

            return;
        }

        $this->update($user->getId(), $user->serialize());
    }
}