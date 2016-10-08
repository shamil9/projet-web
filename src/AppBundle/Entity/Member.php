<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Member extends User
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="member")
     */
    protected $comments;
    
    public function __construct()
    {
        parent::__construct();
        $this->userType = User::TYPE_MEMBER;
        $this->comments = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }
}
