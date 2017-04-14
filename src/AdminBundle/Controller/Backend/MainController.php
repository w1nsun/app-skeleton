<?php

namespace AdminBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Backend/Main:index.html.twig', [
        ]);
    }
}
