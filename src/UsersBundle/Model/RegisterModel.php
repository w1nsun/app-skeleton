<?php

namespace UsersBundle\Model;

use AppBundle\Data\Model;
use MongoDB\BSON\UTCDateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use UsersBundle\Entity\User;
use UsersBundle\Entity\UserManager;

class RegisterModel extends Model implements UserInterface
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $plain_password;

    /**
     * @var string
     */
    protected $password;

    /**
     * @return string
     */
    public function getEmail(): ?string
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
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    /**
     * @param string $plain_password
     */
    public function setPlainPassword(string $plain_password)
    {
        $this->plain_password = $plain_password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }


    public function getRoles()
    {
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
        return User::PASSWORD_SALT;
    }

    public function getUsername()
    {
    }

    public function eraseCredentials()
    {
    }

    public function createUser()
    {
        return new User([
            'email' => $this->email,
            'password' => $this->password,
            'created_date' => new UTCDateTime(time() * 1000),
            'is_active' => true,
        ]);
    }
}