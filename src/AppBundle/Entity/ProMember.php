<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProMemberRepository")
 */
class ProMember extends User implements  UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $street;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $phone;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $website;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $tva;
    
    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="user")
     * @ORM\JoinTable(name="categories_users")
     */
    protected $categories;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sale", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $sales;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Workshop", mappedBy="user", cascade={"remove"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $workshops;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="proMember", cascade={"remove"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $comments;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=255, unique=true)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Favorite", mappedBy="proMember")
     */
    protected $favoredBy;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Newsletter", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $newsletters;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $images;

    public function __construct()
    {
        parent::__construct();
        $this->userType    = User::TYPE_PRO_USER;
        $this->categories  = new ArrayCollection();
        $this->workshops   = new ArrayCollection();
        $this->sales       = new ArrayCollection();
        $this->comments    = new ArrayCollection();
        $this->favoredBy   = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
        $this->images      = new ArrayCollection();
        $this->isActive    = false;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone( $phone )
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription( $description )
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite( $website )
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @param mixed $tva
     */
    public function setTva( $tva )
    {
        $this->tva = $tva;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity( $city )
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet( $street )
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     */
    public function setZip( $zip )
    {
        $this->zip = $zip;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return mixed
     */
    public function getWorkshops()
    {
        return $this->workshops;
    }

    /**
     * @param mixed $workshops
     */
    public function setWorkshops($workshops)
    {
        $this->workshops = $workshops;
    }

    /**
     * @return mixed
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * @param mixed $sales
     */
    public function setSales($sales)
    {
        $this->sales = $sales;
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
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
        return ['ROLE_PRO_USER'];
    }

    /**
     * @return ArrayCollection
     */
    public function getFavoredBy()
    {
        return $this->favoredBy;
    }

    /**
     * @param ArrayCollection $favoredBy
     */
    public function setFavoredBy($favoredBy)
    {
        $this->favoredBy = $favoredBy;
    }

    /**
     * @return mixed
     */
    public function getNewsletters()
    {
        return $this->newsletters;
    }

    /**
     * @param mixed $newsletters
     */
    public function setNewsletters( $newsletters )
    {
        $this->newsletters = $newsletters;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages( $images )
    {
        $this->images = $images;
    }
}
