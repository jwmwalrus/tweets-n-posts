<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Entity\User;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $drp = new RequestParam();
        $drp->name = 'title';
        $drp->requirements = '.+';
        $paramfetcher->addParam($drp);

        $drp = new RequestParam();
        $drp->name = 'content';
        $drp->requirements = '.+';
        $paramfetcher->addParam($drp);

        $drp = new RequestParam();
        $drp->name = 'user_id';
        $drp->requirements = '[1-9]\d*';
        $paramfetcher->addParam($drp);

        $post = new Post();

        try {
            foreach ($paramfetcher->all() as $k => $v) {
                switch ($k) {
                    case 'title':
                        $post->setTitle($v);
                        break;
                    case 'content':
                        $post->setContent($v);
                        break;
                    case 'user_id':
                        $author = $em->getRepository(User::class)
                                     ->find($v);
                        if (empty($author)) {
                            throw new BadRequestHttpException('Provided user_id is invalid');
                        }
                        $post->setAuthor($author);
                        break;
                    default:
                }
            }

            $createdAt = new DateTime();
            $post->setCreatedat($createdAt);

            $em->persist($post);
            $em->flush();
        } catch (DBALException $e) {
            throw new BadRequestHttpException('Error executing SQL statements: ' + $e->getMessage());
        } catch (\Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $data = ['id' => $post->getId()];
        return $this->view($data, Response::HTTP_CREATED);
    }

    /**
     * postEdit
     *
     * @param int $id Unique identifier or the post
     */
    public function postEdit(
        int $id,
        EntityManagerInterface $em,
        ParamFetcher $paramfetcher
    ): View {
        $drp = new RequestParam();
        $drp->name = 'title';
        $drp->requirements = '.+';
        $paramfetcher->addParam($drp);

        $drp = new RequestParam();
        $drp->name = 'content';
        $drp->requirements = '.+';
        $paramfetcher->addParam($drp);

        $post = $em->getRepository(Post::class)
                   ->find($id);

        if (empty($post)) {
            throw new NotFoundHttpException('The requested Post does not exist');
        }

        try {
            foreach ($paramfetcher->all() as $k => $v) {
                switch ($k) {
                    case 'title':
                        $post->setTitle($v);
                        break;
                    case 'content':
                        $post->setContent($v);
                        break;
                    default:
                }
            }

            $em->persist($post);
            $em->flush();
        } catch (DBALException $e) {
            throw new BadRequestHttpException('Error executing SQL statements: ' + $e->getMessage());
        } catch (\Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $data = ['id' => $id];
        return $this->view($data, Response::HTTP_OK);
    }
}
