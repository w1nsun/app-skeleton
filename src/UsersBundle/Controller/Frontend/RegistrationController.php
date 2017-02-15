<?php

namespace UsersBundle\Controller\Frontend;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    public function registerAction(Request $request)
    {


        return $this->render('UsersBundle:Registration:register.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}