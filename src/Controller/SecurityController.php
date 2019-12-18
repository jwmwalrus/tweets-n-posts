<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /**
     * Returns the login view
     *
     */
    public function login(): Response
    {
        $response = $this->render(
            'security/login.html.twig',
            [
            ],
        );

        $response->headers->clearCookie('BEARER');
        return $response;
    }

    /**
     * Returns the registration view
     *
     */
    public function register(): Response
    {
        $response = $this->render(
            'security/register.html.twig',
            [
            ],
        );

        $response->headers->clearCookie('BEARER');
        return $response;
    }

    public function logout()
    {
        // The security layer will intercept this request
    }
}
