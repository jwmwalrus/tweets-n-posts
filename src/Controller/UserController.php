<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * Returns the user view
     *
     * @param int $id Unique identifier for the user
     */
    public function index(
        int $id,
        EntityManagerInterface $em
    ): Response {
        $posts = $em->getRepository(Post::class)
                    ->findBy(
                        ['id' => $id],
                        ['id' => 'DESC']
                    );

        return $this->render('user/index.html.twig', [
            'id' => $id,
            'posts' => $posts,
        ]);
    }
}
