<?php

namespace UsersBundle\Entity;

use AppBundle\Entity\Manager;

class UserManager extends Manager
{
    public function getCollectionName()
    {
        return 'users';
    }
}