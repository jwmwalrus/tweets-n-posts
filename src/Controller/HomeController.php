<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * Returns the home page view
     *
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
        ]);
    }
}
