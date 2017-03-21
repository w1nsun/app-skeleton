<?php

namespace UsersBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('UsersBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }
}