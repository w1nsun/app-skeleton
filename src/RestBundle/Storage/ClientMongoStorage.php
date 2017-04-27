<?php

namespace RestBundle\Storage;

use BlizzardBundle\Storage\AbstractMongoStorage;

class ClientMongoStorage extends AbstractMongoStorage
{
    protected function getCollectionName()
    {
        return 'rest_client';
    }
}