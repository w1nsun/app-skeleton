<?php

namespace UsersBundle\Security;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use UsersBundle\Entity\User;
use UsersBundle\Repository\UserMongoRepository;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserMongoRepository
     */
    private $userRepository;

    /**
     * @param UserMongoRepository $userRepository
     */
    public function __construct(UserMongoRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username
     * @return mixed
     */
    public function loadUserByUsername($username)
    {
        $userData = $this->userRepository->findByUsername(['username' => $username]);
        $user = new User();

        if (!isset($user->uname) || $user->uname !== $username) {
            throw new UsernameNotFoundException(sprintf('User "%s" does not exist.', $username));
        }

        // и возвращаем найденного пользователя
        return $user;
    }

    public function refreshUser(UserInterface $user) {
        var_dump(1);exit;
//        if (!($user instanceof User))
//            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));



//        $_user = $this->collection->findOne(array('_id' => $user->_id));
//
//        if ($_user && $_user instanceof User)
//            $this->logger->info("roles: " .implode(', ',$_user->roles));
//        else
//            throw new UsernameNotFoundException(sprintf('User "%s" does not exist.', $user->uname));
//
//        return $_user;
    }

    public function supportsClass($class) {

        if ($class == 'MyBundle\Models\User')
            return true;

        return false;
    }
}