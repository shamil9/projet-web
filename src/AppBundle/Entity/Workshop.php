<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Workshop
 *
 * @ORM\Table(name="workshops")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkshopRepository")
 */
class Workshop
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime")
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProMember", inversedBy="workshops", fetch="LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $displayFrom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $displayUntil;

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
     * Set name
     *
     * @param string $name
     *
     * @return Workshop
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Workshop
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
     * Set price
     *
     * @param integer $price
     *
     * @return Workshop
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param \DateTime $start
     *
     * @return Workshop
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $end
     *
     * @return Workshop
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Gets the value of displayFrom.
     *
     * @return mixed
     */
    public function getDisplayFrom()
    {
        return $this->displayFrom;
    }

    /**
     * Sets the value of displayFrom.
     *
     * @param mixed $displayFrom the display from
     *
     * @return self
     */
    public function setDisplayFrom($displayFrom)
    {
        $this->displayFrom = $displayFrom;

        return $this;
    }

    /**
     * Gets the value of displayUntil.
     *
     * @return mixed
     */
    public function getDisplayUntil()
    {
        return $this->displayUntil;
    }

    /**
     * Sets the value of displayUntil.
     *
     * @param mixed $displayUntil the display until
     *
     * @return self
     */
    public function setDisplayUntil($displayUntil)
    {
        $this->displayUntil = $displayUntil;

        return $this;
    }
}
