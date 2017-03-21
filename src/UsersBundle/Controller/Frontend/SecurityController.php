<?php

namespace UsersBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UsersBundle\Entity\User;
use UsersBundle\Form\RegistrationType;

class SecurityController extends Controller
{
    public function loginAction()
    {

        //todo: нужно добавить индекс на автоудаление сессий из монги

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('UsersBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    public function registrationAction()
    {
        $a = $this->container->get('repository.mongo.user');

        var_dump($a);exit;

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        if ($form->isSubmitted() && $form->isValid()) {


            return $this->redirect($this->generateUrl(
                'admin_post_show',
                array('id' => 1)
            ));
        }

        return $this->render('UsersBundle:Security:registration.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}