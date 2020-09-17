<?php

namespace App\Security;

use App\Entity\User;
use App\Service\TwitterUserProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TwitterAuthenticator extends AbstractGuardAuthenticator
{

    public function __construct(TwitterUserProvider $twitterProvider, UrlGeneratorInterface $urlGenerator)
    {
        $this->twitterProvider = $twitterProvider;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request)
    {
              
        
        if(strpos($request->getRequestUri(),"/?oauth_token") !== false){
        
            return [
                $request->query->get('oauth_token'),
                $request->query->get('oauth_verifier')
            ];
        }
    }

    public function getCredentials(Request $request)
    {

        return [
            'oauth_token' => $request->query->get('oauth_token'),
            'oauth_verifier' => $request->query->get('oauth_verifier'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // create a random token to identify easily user later on
        $token = new CsrfToken('authenticate', sha1(mt_rand(1, 90000) . 'SALT'));
       
      //  dd($credentials);
        $user = $this->twitterProvider->loadUserFromTwitter();

        if ($user == false) {
            $user = $this->twitterProvider->linkTwitterAccount($credentials['oauth_token'],$credentials['oauth_verifier']);
        }
        return $user;

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse($this->urlGenerator->generate('homepage'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();

        if (in_array( "ROLE_BRAND", $user->getRoles() )){
            return new RedirectResponse($this->urlGenerator->generate('user_dashboard', ['id' => $user->getId()]));
        }
        if (in_array( "ROLE_INFLUENCER", $user->getRoles() )){
            return new RedirectResponse($this->urlGenerator->generate('user_dashboard', ['id' => $user->getId()]));
        }
        if (in_array( "ROLE_ADMIN", $user->getRoles() )){
            return new RedirectResponse($this->urlGenerator->generate('easyadmin'));
        }
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
