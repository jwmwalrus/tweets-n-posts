<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * Returns the home page view
     *
     */
    public function index(EntityManagerInterface $em): Response
    {
        $posts = $em->getRepository(Post::class)
                    ->findBy(
                        [],
                        ['id' => 'DESC'],
                        3
                    );

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
