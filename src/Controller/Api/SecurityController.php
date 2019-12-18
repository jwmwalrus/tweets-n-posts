<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractFOSRestController
{
    /**
     * Logs a user in
     *
     */
    public function postLogin(
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
    public function postRegister(
        EntityManagerInterface $em,
        ParamFetcher $paramfetcher
    ): View {
        $data = [];
        return $this->view($data, Response::HTTP_OK);
    }
}
