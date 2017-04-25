<?php

namespace RestBundle\Controller\Backend;

use RestBundle\Component\TokenGenerator;
use RestBundle\Entity\Client;
use RestBundle\Form\ClientType;
use RestBundle\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends Controller
{
    public function createAction(Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client, [
            'roles' => $this->container->getParameter('rest_roles'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ClientRepository $clientRepository */
            $clientRepository = $this->container->get('rest.repository.client');
            $clientRepository->save($client);

            return $this->redirectToRoute('rest_admin_client_index');
        }

        return $this->render('RestBundle:Backend/Client:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function indexAction()
    {
        /** @var ClientRepository $clientRepository */
        $clientRepository = $this->container->get('rest.repository.client');
        $clients = $clientRepository->findAll();

        return $this->render('RestBundle:Backend/Client:index.html.twig', [
            'clients' => $clients,
        ]);
    }

    public function generateTokenAction()
    {
        /** @var TokenGenerator $tokenGenerator */
        $tokenGenerator = $this->container->get('rest.component.token_generator');

        return new JsonResponse($tokenGenerator->generate());
    }
}
