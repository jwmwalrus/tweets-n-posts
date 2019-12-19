<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractFOSRestController
{
    /**
     * Logs a user in
     *
     */
    public function postLogin(): View
    {
        return $this->view([], Response::HTTP_OK);
    }

    /**
     * Registers a user
     *
     */
    public function postRegister(
        EntityManagerInterface $em,
        ParamFetcher $paramfetcher
    ): View {
        $drp = new RequestParam();
        $drp->name = 'name';
        $drp->requirements = '.+';
        $paramfetcher->addParam($drp);

        $drp = new RequestParam();
        $drp->name = 'username';
        $drp->requirements = '.+';
        $paramfetcher->addParam($drp);

        $drp = new RequestParam();
        $drp->name = 'email';
        $drp->requirements = '.+';
        $paramfetcher->addParam($drp);

        $drp = new RequestParam();
        $drp->name = 'password';
        $drp->requirements = '.+';
        $paramfetcher->addParam($drp);

        $drp = new RequestParam();
        $drp->name = 'twitterid';
        $drp->requirements = '.+';
        $paramfetcher->addParam($drp);

        $user = new User();

        foreach ($paramfetcher->all() as $k => $v) {
            switch ($k) {
                case 'name':
                    $user->setName($v);
                    break;
                case 'username':
                    $user->setUsername($v);
                    break;
                case 'email':
                    $user->setEmail($v);
                    break;
                case 'password':
                    $password = password_hash(
                        $v,
                        PASSWORD_BCRYPT,
                        [
                            'cost' => 14,
                        ]
                    );
                    $user->setPassword($password);
                    break;
                case 'twitterid':
                    $user->setTwitterid($v);
                    break;
                default:
            }
        }

        $em->persist($user);
        $em->flush();

        $data = [
            'id' => $user->getId(),
        ];

        return $this->view($data, Response::HTTP_CREATED);
    }
}
