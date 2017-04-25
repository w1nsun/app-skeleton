<?php

namespace UsersBundle\Controller\Frontend;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use UsersBundle\Entity\User;
use UsersBundle\Factory\UserFactory;
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

        $fb = new Facebook([
            'app_id' => $this->container->getParameter('facebook_app_id'),
            'app_secret' => $this->container->getParameter('facebook_app_secret'),
            'default_graph_version' => 'v2.5',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl(
            $this->generateUrl('users_security_fb_callback', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ['email']
        );

        return $this->render('UsersBundle:Frontend/Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'fb_login_url'  => $loginUrl,
        ));
    }

    public function registrationAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserPasswordEncoderInterface $encoder */
            $encoder = $this->container->get('security.password_encoder');
            $encodedPassword = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encodedPassword);
            /** @var UserRepository $userRepository */
            $userRepository = $this->container->get('users.repository.user');
            $userRepository->save($user);

            return new RedirectResponse('/');
        }

        return $this->render('UsersBundle:Frontend/Security:registration.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function loginCheckAction()
    {
        return $this->redirectToRoute('homepage');
    }

    public function fbCallbackAction(Request $request)
    {
        $fb = new Facebook([
            'app_id' => $this->container->getParameter('facebook_app_id'),
            'app_secret' => $this->container->getParameter('facebook_app_secret'),
            'default_graph_version' => 'v2.5',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (isset($accessToken)) {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string) $accessToken;

            // Now you can redirect to another page and use the
            // access token from $_SESSION['facebook_access_token']
        }

        try {
            $response = $fb->get('/me?fields=id,name,email', $accessToken);
            $userNode = $response->getGraphUser();
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        /** @var UserRepository $userRepository */
        $userRepository = $this->container->get('users.repository.user');
        $user = $userRepository->findBySocial('facebook', (string) $userNode->getId());

        if (!$user) {
            $user = new User();
            $user->setUsername($userNode->getEmail());
            $user->setPassword(md5(time()));
            $user->setEmail($userNode->getEmail());
            $user->setSocial('facebook');
            $user->setSocialId((string) $userNode->getId());
            $userRepository->save($user);
        }

        $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_secured_area', serialize($token));

        return $this->redirectToRoute('homepage');
    }
}