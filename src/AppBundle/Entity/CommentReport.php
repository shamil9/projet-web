<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentReport
 *
 * @ORM\Table(name="comment_reports")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentReportRepository")
 */
class CommentReport
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
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Comment", inversedBy="reports", cascade={"persist"})
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $comment;

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
     * @param string $description
     *
     * @return Report
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Report
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Gets the value of comment.
     *
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the value of comment.
     *
     * @param mixed $comment the comment
     *
     * @return self
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }
}

