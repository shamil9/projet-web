<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Member extends User implements \Serializable, UserInterface
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="member", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Favorite", mappedBy="member", cascade={"remove"}, orphanRemoval=true)
     */
    protected $favorites;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\NewsletterSubscriber", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    protected $subscribed;

    public function __construct()
    {
        parent::__construct();
        $this->userType = User::TYPE_MEMBER;
        $this->comments = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->isActive = true;
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

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @return mixed
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * @param mixed $favorites
     */
    public function setFavorites($favorites)
    {
        $this->favorites = $favorites;
    }

    /**
     * @return mixed
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }
}
