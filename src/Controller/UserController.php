<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
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
        $user = $em->getRepository(User::class)->find($id);
        $posts = $em->getRepository(Post::class)
                    ->findBy(
                        ['author' => $user],
                        ['id' => 'DESC']
                    );

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}
