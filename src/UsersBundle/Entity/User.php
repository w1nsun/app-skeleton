<?php

namespace UsersBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class User implements AdvancedUserInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var bool
     */
    private $isActive = true;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @var string
     */
    private $social;

    /**
     * @var string
     */
    private $socialId;

    /**
     * @var array
     */
    private $roles = [];

    public function __construct()
    {
        $this->setRoles(['ROLE_USER']);
        $this->setCreatedAt(time());
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail()
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
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return 'salt';
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @param array $state
     * @return User
     */
    public static function fromState(array $state): User
    {
        $self = new self();
        $self->setId((string) $state['_id']);
        $self->setUsername($state['username']);
        $self->setEmail($state['email']);
        $self->setPassword($state['password']);
        $self->setIsActive($state['is_active']);
        $self->setCreatedAt($state['created_at']);
        $self->setRoles($state['roles']);
        $self->setSocial($state['social']);
        $self->setSocialId($state['social_id']);

        return $self;
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

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedAt(int $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return (bool) $this->getId();
    }

    /**
     * @return string
     */
    public function getSocial(): ?string
    {
        return $this->social;
    }

    /**
     * @param string $social
     */
    public function setSocial(?string $social)
    {
        $this->social = $social;
    }

    /**
     * @return string
     */
    public function getSocialId(): ?string
    {
        return $this->socialId;
    }

    /**
     * @param string $socialId
     */
    public function setSocialId(?string $socialId)
    {
        $this->socialId = $socialId;
    }
}