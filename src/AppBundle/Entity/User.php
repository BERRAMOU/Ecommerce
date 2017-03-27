<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateurs")
 *  * @ORM\Entity(repositoryClass="AppBundle\Repository\UtilisateursRepository")
 */



class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\OneToMany(targetEntity="Ecommerce\EcommerceBundle\Entity\Commandes",mappedBy="utilisateur",cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $commandes;


    /**
     * @ORM\OneToMany(targetEntity="Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses",mappedBy="utilisateur",cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $adresses;

    /**
     * User constructor.
     */
    public function __construct()
    {

        parent::__construct();
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->adresses = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add commandes
     *
     * @param \Ecommerce\EcommerceBundle\Entity\Commandes $commandes
     * @return User
     */
    public function addCommande(\Ecommerce\EcommerceBundle\Entity\Commandes $commandes)
    {

        $this->commandes[] = $commandes;

        return $this;
    }

    /**
     * Remove commandes
     *
     * @param \Ecommerce\EcommerceBundle\Entity\Commandes $commandes
     */
    public function removeCommande(\Ecommerce\EcommerceBundle\Entity\Commandes $commandes)
    {

        $this->commandes->removeElement($commandes);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {

        return $this->commandes;
    }

    /**
     * Add adresses
     *
     * @param \Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses $adresses
     * @return User
     */
    public function addAdress(\Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses $adresses)
    {

        $this->adresses[] = $adresses;

        return $this;
    }

    /**
     * Remove adresses
     *
     * @param \Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses $adresses
     */
    public function removeAdress(\Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses $adresses)
    {

        $this->adresses->removeElement($adresses);
    }

    /**
     * Get adresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdresses()
    {

        return $this->adresses;
    }
}
