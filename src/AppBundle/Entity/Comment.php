<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CommentReport", mappedBy="comment", cascade={"remove"}, orphanRemoval=true)
     */
    private $reports;

    /**
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Member", inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $member;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProMember", inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(name="pro_member_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $proMember;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

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
     * Set description
     *
     * @param string $comment
     *
     * @return Comments
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
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

    /**
     * Gets the value of reports.
     *
     * @return mixed
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * Sets the value of reports.
     *
     * @param mixed $reports the reports
     *
     * @return self
     */
    public function setReports($reports)
    {
        $this->reports = $reports;

        return $this;
    }
}

