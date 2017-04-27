<?php

namespace UsersBundle\Storage;

use BlizzardBundle\Storage\AbstractMongoStorage;

class UserMongoStorage extends AbstractMongoStorage
{
    protected function getCollectionName()
    {
        return 'users';
    }
}