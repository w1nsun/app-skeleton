<?php

namespace UsersBundle\Controller\Frontend;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
            $user = $registerModel->createUser();
            $this->container->get('users.user_manager')->save($user);

            return $this->redirectToRoute('homepage');
        }

//        $fb = new Facebook([
//            'app_id' => $this->container->getParameter('facebook_app_id'),
//            'app_secret' => $this->container->getParameter('facebook_app_secret'),
//            'default_graph_version' => 'v2.2',
//        ]);
//
//        $helper = $fb->getRedirectLoginHelper();
//        $permissions = ['email'];
//        $loginUrl = $helper->getLoginUrl(
//            $this->generateUrl('users_registration_fb_callback', [], UrlGeneratorInterface::ABSOLUTE_URL),
//            $permissions
//        );

        return $this->render('UsersBundle:Registration:register.html.twig', [
            'form' => $form->createView(),
            'fb_login_url' => ''//$loginUrl,
        ]);
    }

    public function fbCallbackAction(Request $request)
    {
        $fb = new Facebook([
            'app_id' => $this->container->getParameter('facebook_app_id'),
            'app_secret' => $this->container->getParameter('facebook_app_secret'),
            'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
            var_dump($e->getMessage());
            exit;
            throw new NotAcceptableHttpException();
        } catch(FacebookSDKException $e) {
            var_dump($e->getMessage());
            exit;
            throw new NotAcceptableHttpException();
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        echo '<h3>Access Token</h3>';
        var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        echo '<h3>Metadata</h3>';
        var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->container->getParameter('facebook_app_id')); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;
    }
}