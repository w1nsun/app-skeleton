<?php

namespace UsersBundle\Controller\Frontend;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UsersBundle\Entity\User;
use UsersBundle\Form\RegisterType;
use UsersBundle\Model\RegisterModel;

class RegistrationController extends Controller
{
    public function registerAction(Request $request)
    {
        $registerModel = new RegisterModel();
        $form = $this->createForm(RegisterType::class, $registerModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this
                            ->get('security.password_encoder')
                            ->encodePassword($registerModel, $registerModel->getPlainPassword());

            $registerModel->setPassword($password);
            $registerModel->register();

            return $this->redirectToRoute('homepage');
        }


        return $this->render('UsersBundle:Registration:register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}