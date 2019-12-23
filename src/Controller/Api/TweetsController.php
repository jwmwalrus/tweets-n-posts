<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Entity\Tweet;
use App\Entity\User;
use App\Service\TwitterFeeds;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TweetsController extends AbstractFOSRestController
{
    /**
     * postNew
     *
     */
    public function getList(
        EntityManagerInterface $em,
        ParamFetcher $paramfetcher,
        TwitterFeeds $tf
    ): View {
        $drp = new QueryParam();
        $drp->name = 'twitterid';
        $drp->requirements = '.+';
        $drp->nullable = true;
        $paramfetcher->addParam($drp);

        $drp = new QueryParam();
        $drp->name = 'limit';
        $drp->requirements = '[1-9]\d*';
        $drp->nullable = true;
        $paramfetcher->addParam($drp);

        $drp = new QueryParam();
        $drp->name = 'norefresh';
        $drp->requirements = '[01]';
        $drp->nullable = true;
        $paramfetcher->addParam($drp);

        $findBy = [];
        $limit = null;
        $noRefresh = false;

        try {
            foreach ($paramfetcher->all() as $k => $v) {
                if ($v === null) {
                    continue;
                }

                switch ($k) {
                    case 'twitterid':
                        $findBy{'owner'} = $em->getRepository(User::class)
                                              ->findOneBy(['twitterid' => $v]);
                        break;
                    case 'limit':
                        $limit = intval($v);
                        break;
                    case 'norefresh':
                        $noRefresh = boolval($v);
                        break;
                    default:
                }
            }

            if (!$noRefresh) {
                $tf->update();
            }

            $data = $em->getRepository(Tweet::class)
                       ->findBy(
                           $findBy,
                           ['timestamp' => 'DESC', 'id' => 'DESC'],
                           $limit
                       );
        } catch (DBALException $e) {
            throw new BadRequestHttpException('Error executing SQL statements: ' + $e->getMessage());
        } catch (\Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->view($data, Response::HTTP_OK);
    }
}
