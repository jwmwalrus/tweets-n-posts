<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * Returns the user view
     *
     * @param int $id Unique identifier for the user
     */
    public function index(int $id): Response
    {
        return $this->render('user/index.html.twig', [
            'id' => $id,
        ]);
    }
}
