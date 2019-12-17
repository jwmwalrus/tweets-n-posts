<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * API actions for JWT
 *
 * @see AbstractController
 */
class TokensController extends AbstractController
{
    /**
     * Return a new token for the given basic authentication
     *
     */
    public function postNew(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordEncoderInterface $upei,
        JWTEncoderInterface $jwtencoder
    ): JsonResponse {
        try {
            $user = $doctrine->getManager()
                             ->getRepository(User::class)
                             ->loadUserByUsername($request->getUser());
        } catch (\Throwable $e) {
            throw $e;
            throw new BadCredentialsException();
        }

        $valid = $upei->isPasswordValid($user, $request->getPassword());

        if (!$valid) {
            throw new BadCredentialsException();
        }

        $iat = time();
        $exp = $iat + 36000; // 10 hours expiration
        $token = $jwtencoder
                    ->encode(
                        [
                            'iat' => $iat,
                            'nbf' => $iat,
                            'exp' => $exp,
                            'id' => $user->getId(),
                            'username' => $user->getUsername(),
                            'roles' => $user->getRoles(),
                            'name' => $user->getName(),
                        ]
                    );

        $cookie = new Cookie('MOTTRIZBEARER', $token, $exp);
        $response = new JsonResponse(['token' => $token]);
        $response->headers->setCookie($cookie);

        return $response;
    }
}
