<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\CookieTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Performs authentication-related tasks
 *
 * @see AbstractGuardAuthenticator
 */
class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * jwtencoder
     *
     * @var JWTEncoderInterface
     */
    protected $jwtencoder;

    /**
     * doctrine
     *
     * @var ManagerRegistry
     */
    protected $doctrine;


    public function __construct(
        JWTEncoderInterface $jwtencoder,
        ManagerRegistry $doctrine
    ) {
        $this->jwtencoder = $jwtencoder;
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Request $request)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        if (!$token) {
            $extractor = new CookieTokenExtractor(
                'BEARER'
            );

            $token = $extractor->extract($request);

            if (!$token) {
                throw new UnauthorizedHttpException('Token not found');
            }
        }

        return $token;
    }

    /**
     * {@inheritDoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $data = $this->jwtencoder->decode($credentials);
        } catch (JWTDecodeFailureException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }

        $username = $data['username'];

        try {
            $user = $this->doctrine
                         ->getManager()
                         ->getRepository(User::class)
                         ->loadUserByUsername($username);
        } catch (\Throwable $t) {
            throw new CustomUserMessageAuthenticationException('Invalid User');
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    /**
     * {@inheritDoc}
     *
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'error' => 'auth required',
        ], 401);
    }
}
