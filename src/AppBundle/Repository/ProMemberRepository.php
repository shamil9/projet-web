<?php


namespace AppBundle\Repository;


use AppBundle\Entity\ProMember;
use Doctrine\ORM\EntityRepository;

class ProMemberRepository extends EntityRepository
{
    /**
     * @param ProMember $user
     * @return \Doctrine\ORM\Query
     */
    public function findProUserSuggestions(ProMember $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('user')
            ->from('AppBundle:ProMember', 'user')
            ->innerJoin('user.categories', 'cat', 'WITH', 'cat.id IN (:category)')
            ->setMaxResults(4)
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
            ->from('AppBundle:ProMember', 'users')
            ->orderBy('users.registrationDate', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    public function findCitiesWithProUsers()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select(['user.city', $qb->expr()->count('user.id')])
            ->from('AppBundle:ProMember', 'user')
            ->groupBy('user.city')
            ->getQuery()
            ->getResult();
    }
}
