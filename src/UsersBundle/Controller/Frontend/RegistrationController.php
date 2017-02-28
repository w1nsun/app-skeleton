<?php

namespace UsersBundle\Controller\Frontend;
use Facebook\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

        $fb = new Facebook([
            'app_id' => $this->container->getParameter('facebook_app_id'),
            'app_secret' => $this->container->getParameter('facebook_app_secret'),
            'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email'];
        $loginUrl = $helper->getLoginUrl($this->generateUrl('users_registration_fb_callback'), $permissions);

        return $this->render('UsersBundle:Registration:register.html.twig', [
            'form' => $form->createView(),
            'fb_login_url' => htmlspecialchars($loginUrl),
        ]);
    }

    public function fbCallbackAction(Request $request)
    {
        var_dump($request);
    }
}