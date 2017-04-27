<?php

namespace RestBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class Client implements AdvancedUserInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $resources = [];

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @var bool
     */
    private $isActive = true;

    public function __construct()
    {
        $this->setCreatedAt(time());
    }


    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive();
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->getToken();
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getName();
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param array $resources
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param array $state
     * @return Client
     */
    public static function fromState(array $state)
    {
        $self = new self();
        $self->setId((string) $state['_id']);
        $self->setName($state['name']);
        $self->setToken($state['token']);
        $self->setResources($state['resources']);
        $self->setIsActive($state['is_active']);
        $self->setCreatedAt($state['created_at']);
        $self->setRoles($state['roles']);

        return $self;
    }
}
