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
        return $this->render('security/login.html.twig', [
        ]);
    }

    /**
     * Returns the registration view
     *
     */
    public function register(): Response
    {
        return $this->render('security/register.html.twig', [
        ]);
    }

    public function logout()
    {
        // The security layer will intercept this request
    }
}
