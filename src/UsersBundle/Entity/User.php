<?php

namespace UsersBundle\Entity;

use AppBundle\Data\Entity;
use MongoDB\BSON\UTCDateTime;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends Entity implements UserInterface
{
    const PASSWORD_SALT = 'testsalt';

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var bool
     */
    protected $is_active = false;

    /**
     * @var UTCDateTime
     */
    protected $created_date;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activate user
     */
    public function activate()
    {
        $this->is_active = true;
    }

    /**
     * @return UTCDateTime
     */
    public function getCreatedDate(): UTCDateTime
    {
        return $this->created_date;
    }

    /**
     * @param UTCDateTime $created_date
     */
    public function setCreatedDate(UTCDateTime $created_date)
    {
        $this->created_date = $created_date;
    }









    public function getRoles()
    {
        return [];
    }

    public function getPassword()
    {
        return '';
    }

    public function getSalt()
    {
        return self::PASSWORD_SALT;
    }

    public function getUsername()
    {
        return '';
    }

    public function eraseCredentials()
    {
    }

    public function bsonSerialize()
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    public function bsonUnserialize(array $data)
    {
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
}