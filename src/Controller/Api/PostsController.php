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
     * postNew
     *
     */
    public function postNew(
        EntityManagerInterface $em,
        ParamFetcher $paramfetcher
    ): View {
        $data = [];
        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * postEdit
     *
     * @param int $id Unique identifier or the post
     */
    public function postEdit(
        int $id,
        EntityManagerInterface $em
    ): View {
        $data = [];
        return $this->view($data, Response::HTTP_OK);
    }
}
