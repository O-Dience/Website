<?php

namespace App\Security;

use App\Entity\User;
use App\Service\GoogleUserProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class GoogleAuthenticator extends AbstractGuardAuthenticator
{

    public function __construct(GoogleUserProvider $googleProvider, UrlGeneratorInterface $urlGenerator, SessionInterface $session)
    {
        $this->googleProvider = $googleProvider;
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
    }

    public function supports(Request $request)
    {
        // if ($request->getPathInfo() == ' //TODO ') {
            return $request->query->get('code');
        // }
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

        $user = $this->googleProvider->loadUserFromGoogle($credentials['code']);
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($user->getRoles() === ["ROLE_USER"]) {
            return false;
        } else {
            return true;
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->session->getFlashBag()->add('danger', 'Veuillez vous inscrire avant de pouvoir vous connecter');
        // TODO: Remove error message and replace it with filled registration form
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
