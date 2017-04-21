<?php

namespace UsersBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UsersBundle\Repository\UserRepository;

class ManageController extends Controller
{
    public function indexAction()
    {
        /** @var UserRepository $usersRepository */
        $usersRepository = $this->container->get('users.repository.user');
        $users = $usersRepository->findAll();

        return $this->render('UsersBundle:Backend/Manage:index.html.twig', [
            'users' => $users,
        ]);
    }
}