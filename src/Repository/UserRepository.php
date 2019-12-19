<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository extends EntityRepository implements UserProviderInterface
{
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
        return $class === User::class;
    }
}
