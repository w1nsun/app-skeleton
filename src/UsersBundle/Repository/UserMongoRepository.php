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

//    public function save(User $user)
//    {
//        if ($entity->isNew()) {
//            $id = $this->insert($entity->bsonSerialize());
//            $entity->setId($id);
//
//            return;
//        }
//
//        $this->update($entity->getId(), $entity->bsonSerialize());
//    }
}