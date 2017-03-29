<?php

namespace UsersBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use UsersBundle\Entity\User;
use UsersBundle\Form\RegistrationType;
use UsersBundle\Repository\UserRepository;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('UsersBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    public function registrationAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserRepository $userRepository */
            $userRepository = $this->container->get('repository.user');
            $userRepository->save($user);

            return new RedirectResponse('/');
        }

        return $this->render('UsersBundle:Security:registration.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}