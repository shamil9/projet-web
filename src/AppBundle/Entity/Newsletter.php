<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Newsletter
 *
 * @ORM\Table(name="newsletters")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewsletterRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Newsletter
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="posted_at", type="datetime")
     */
    private $postedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="document", type="string", length=255)
     * @Assert\File(mimeTypes={"application/pdf", "application/x-pdf"})
     */
    private $document;

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
     * Set title
     *
     * @param string $title
     *
     * @return Newsletter
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set postedAt
     *
     * @param \DateTime $postedAt
     *
     * @return Newsletter
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    /**
     * Get postedAt
     *
     * @return \DateTime
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * Set document
     *
     * @param string $document
     *
     * @return Newsletter
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeDocument()
    {
            unlink(__DIR__ . '/../../../web/assets/img/uploads/newsletters/' . $this->document);
    }
}

