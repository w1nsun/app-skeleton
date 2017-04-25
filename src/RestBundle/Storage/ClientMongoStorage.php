<?php

namespace RestBundle\Storage;

use BlizzardBundle\Storage\AbstractMongoStorage;

class ClientMongoStorage extends AbstractMongoStorage
{
    protected function getCollectionName(): string
    {
        return 'rest_client';
    }
}