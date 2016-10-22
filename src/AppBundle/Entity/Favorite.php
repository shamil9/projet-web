<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favorite
 *
 * @ORM\Table(name="favorite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FavoriteRepository")
 */
class Favorite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Member", inversedBy="favorites")
     */
    protected $member;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProMember", inversedBy="favoredBy")
     */
    protected $proMember;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param mixed $member
     */
    public function setMember($member)
    {
        $this->member = $member;
    }

    /**
     * @return mixed
     */
    public function getProMember()
    {
        return $this->proMember;
    }

    /**
     * @param mixed $proMember
     */
    public function setProMember($proMember)
    {
        $this->proMember = $proMember;
    }
}

