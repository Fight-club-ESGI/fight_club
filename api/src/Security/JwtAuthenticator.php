<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class JwtAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization') && strpos($request->headers->get('Authorization'), 'Bearer ') === 0;
    }

    public function getCredentials(Request $request)
    {
        $token = str_replace('Bearer ', '', $request->headers->get('Authorization'));

        try {
            $payload = $this->jwtManager->decode($token);
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException('Invalid token');
        }

        return [
            'username' => $payload['username'],
            'roles' => $payload['roles'],
            'token' => $token
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'error' => $exception->getMessageKey()
        ], 401);
    }

    public function onAuthenticationSuccess(Request $request, $token, UserInterface $user)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'error' => 'Unauthorized'
        ], 401);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
