<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Member extends User
{
    public function __construct()
    {
        parent::__construct();
        $this->userType = User::TYPE_MEMBER;
    }
}
