<?php


namespace AppBundle\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository; 

class UserRepository extends EntityRepository
{
    /**
     * @param $user User
     * @return \Doctrine\ORM\Query
     */
    public function findSuggestions(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb->select('user')
            ->from('AppBundle:User', 'user')
            ->leftJoin('user.category', 'cat', 'WITH', 'cat.id IN (:category)')
            ->where('user.zip = :zip')
            ->andWhere('user.id != :id')
            ->setParameters([
                'zip' => $user->getZip(),
                'id' => $user->getId(),
                'category' => $user->getCategory(),
            ])
            ->getQuery()->getResult();

        return $query;
    }
}
