<?php

namespace App\Security;


use App\Service\InstagramUserProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class InstagramAuthenticator extends AbstractGuardAuthenticator
{

    public function __construct(InstagramUserProvider $instagramProvider, UrlGeneratorInterface $urlGenerator)
    {
        $this->instagramProvider = $instagramProvider;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request)
    {
        if ($request->getPathinfo() === "/insta") {
            return $request->query->get('code');
        }
    }

    public function getCredentials(Request $request)
    {


        return [
            'code' => $request->query->get('code'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {



        // create a random token to identify easily user later on
        $token = new CsrfToken('authenticate', sha1(mt_rand(1, 90000) . 'SALT'));


        $user = $this->instagramProvider->loadUserFromInsta($credentials['code']);



        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Manage registering

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {


        $session = new Session();
        $session->start();

        // add flash messages
        $session->getFlashBag()->add(
            'warning',
            'Il y a un problème, veuillez réessayer :( '
        );

        $session->getFlashBag()->add('error', 'Failed to update name');
        $session->getFlashBag()->add('error', 'Another error');

        return new RedirectResponse($this->urlGenerator->generate('homepage'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();

        if (in_array("ROLE_BRAND", $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('user_dashboard', ['id' => $user->getId()]));
        }
        if (in_array("ROLE_INFLUENCER", $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('user_dashboard', ['id' => $user->getId()]));
        }
        if (in_array("ROLE_ADMIN", $user->getRoles())) {
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
