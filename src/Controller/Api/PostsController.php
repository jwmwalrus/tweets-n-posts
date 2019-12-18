<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends AbstractFOSRestController
{
    /**
     * Logs a user in
     *
     */
    public function getList(
        EntityManagerInterface $em,
        ParamFetcher $paramfetcher
    ): View {
        $data = [];
        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Registers a user
     *
     */
    /**
     * Returns the contents of the given post
     *
     * @param int $id Unique identifier of the post
     */
    public function getEdit(
        int $id,
        EntityManagerInterface $em
    ): View {
        $data = [];
        return $this->view($data, Response::HTTP_OK);
    }
}
