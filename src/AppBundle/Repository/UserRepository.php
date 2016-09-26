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
        return $this->getEntityManager()->createQuery(
            "SELECT users 
             FROM AppBundle:User users
             WHERE users.zip = $user->zip AND $user->id != users.id"
        )->getResult();
    }
}
