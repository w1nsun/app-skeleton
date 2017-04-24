<?php

namespace RestBundle\Controller\Backend;

use RestBundle\Entity\Client;
use RestBundle\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends Controller
{
    public function createAction(Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('RestBundle:Backend/Client:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
