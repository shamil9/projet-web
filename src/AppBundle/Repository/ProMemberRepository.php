<?php


namespace AppBundle\Repository;


use AppBundle\Entity\ProMember;
use Doctrine\ORM\EntityRepository;

class ProMemberRepository extends EntityRepository
{
    /**
     * @param ProMember $user
     * @return array
     */
    public function findProUserSuggestions(ProMember $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('user')
            ->from('AppBundle:ProMember', 'user')
            ->innerJoin('user.categories', 'cat', 'WITH', 'cat.id IN (:category)')
            ->addSelect('cat')
            ->andWhere('user.zip = :zip')
            ->andWhere('user.id != :id')
            ->setMaxResults(4)
            ->setParameters([
                'zip' => $user->getZip(),
                'id' => $user->getId(),
                'category' => $user->getCategories(),
            ])
            ->getQuery()->getResult();
    }

    /**
     * @return array
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

        return  $qb->select(['user.city', $qb->expr()->count('user.id') . ' as totalUsers'])
            ->from('AppBundle:ProMember', 'user')
            ->groupBy('user.city')
            ->getQuery()
            ->getResult();


    }

    public function search($userName, $city, $id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('users')
            ->from('AppBundle:ProMember', 'users')
            ->leftJoin('users.categories', 'cat')
            ->addSelect('cat');

            if ($userName) {
                $qb->andWhere('users.name LIKE :name');
                $qb->setParameter('name', "%{$userName}%");
            }

            if ($city) {
                $qb->andWhere('users.city = :city');
                $qb->setParameter('city', $city);
            }

            if ($id) {
                $qb->andWhere('cat.id = :id');
                $qb->setParameter('id', $id);
            }

            return $qb->getQuery()->getResult();
    }
}
