<?php

namespace App\Repository;

// use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{
    // public function __construct(ManagerRegistry $registry)
    // {
        // parent::__construct($registry, User::class);
    // }

    public function loadUserByUsername($username)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $user = $qb->select('u')
                   ->from('App:User', 'u')
                   ->where('u.username = :username')
                   ->setParameter('username', $username)
                   ->getQuery()
                   ->getSingleResult();

        $user->addRole('ROLE_USER');

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        #return $this->getEntityName() === $class
        #    || is_subclass_of($class, $this->getEntityName());
        return $class === User::class;
    }
}
