<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Member", mappedBy="avatar")
     */
    private $member;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ProMember", mappedBy="picture")
     */
    private $proMember;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;


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
    public function setMember( $member )
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
    public function setProMember( $proMember )
    {
        $this->proMember = $proMember;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath( $path )
    {
        $this->path = $path;
    }
}

