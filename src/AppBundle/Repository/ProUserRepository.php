<?php


namespace AppBundle\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository; 

class ProUserRepository extends EntityRepository
{
    /**
     * @param $user User
     * @return \Doctrine\ORM\Query
     */
    public function findProUserSuggestions(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('user')
            ->from('AppBundle:User', 'user')
            ->leftJoin('user.categories', 'cat', 'WITH', 'cat.id IN (:category)')
            ->where('user.zip = :zip')
            ->andWhere('user.id != :id')
            ->setParameters([
                'zip' => $user->getZip(),
                'id' => $user->getId(),
                'category' => $user->getCategories(),
            ])
            ->getQuery()->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findLatestProUsers()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('users')
            ->from('AppBundle:User', 'users')
            ->orderBy('users.registrationDate', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }
}
