<?php

namespace UsersBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManageController extends Controller
{
    public function indexAction()
    {
        return $this->render('UsersBundle:Backend/Manage:index.html.twig', [
        ]);
    }
}