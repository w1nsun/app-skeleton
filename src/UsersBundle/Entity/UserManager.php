<?php

namespace UsersBundle\Entity;

use AppBundle\Data\Manager;

class UserManager extends Manager
{
    public function getCollectionName()
    {
        return 'users';
    }
}