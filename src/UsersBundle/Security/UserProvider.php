<?php

namespace UsersBundle\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use UsersBundle\Entity\UserManager;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param array $params
     * @param UserManager $userManager
     */
    public function __construct(array $params, UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param string $username
     * @return mixed
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userManager->findById(array('email' => $username));

        // выбрасываем спец. исключение, если пользователь не найден.
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