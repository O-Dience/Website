<?php

namespace App\Security;

use App\Entity\User;
use App\Service\GoogleUserProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class GoogleAuthenticator extends AbstractGuardAuthenticator
{

    public function __construct(GoogleUserProvider $googleProvider)
    {
        $this->googleProvider = $googleProvider;
    }

    public function supports(Request $request)
    {
        return $request->query->get('code');
    }

    public function getCredentials(Request $request)
    {
        return [
            'code' => $request->query->get('code'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $this->googleProvider->loadUserFromGoogle($credentials['code']);
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // Get user to check for his role
        $user = $token->getUser();

        if (in_array( "ROLE_BRAND", $user->getRoles() )){
            return new RedirectResponse($this->urlGenerator->generate('user_dashboard', ['id' => $user->getId()]));
        }
        if (in_array( "ROLE_INFLUENCER", $user->getRoles() )){
            return new RedirectResponse($this->urlGenerator->generate('user_dashboard', ['id' => $user->getId()]));
        }
        // If user have no role assigned yet
        else{

            return new RedirectResponse($this->urlGenerator->generate('app_register_oauthUser'));
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
