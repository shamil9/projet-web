<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Localite
 *
 * @ORM\Table(name="localitees")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocaliteRepository")
 */
class Localite
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
     * @var int
     *
     * @ORM\Column(name="localite", type="integer")
     */
    private $localite;


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
     * Set localite
     *
     * @param integer $localite
     *
     * @return Localite
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * Get localite
     *
     * @return int
     */
    public function getLocalite()
    {
        return $this->localite;
    }
}

