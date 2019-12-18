<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{
    /**
     * Returns the post edition view
     *
     * @param int $id Unique identifier of the post
     */
    public function edit(int $id): Response
    {
        return $this->render('post/edit.html.twig', [
            'id' => $id,
        ]);
    }

    /**
     * Returns the post creation view
     *
     */
    public function new(): Response
    {
        return $this->render('post/new.html.twig', [
        ]);
    }
}
