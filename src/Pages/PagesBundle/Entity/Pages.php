<?php

namespace Pages\PagesBundle\Entity;
use Pages\PagesBundle\Validator\Constraints as CustomAssert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pages
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity(repositoryClass="Pages\PagesBundle\Repository\PagesRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleteAt" , timeAware=false)
 */
class Pages
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    /**
     * @Gedmo\Slug(fields={"created","titre"})
     * @ORM\Column(length=128 , unique=true)
     */
    private $slug;


    /**
     * @ORM\Column(name="deleteAt" , type="datetime" , nullable=true)
     */

    private $deleteAt;


    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $deleteAt
     */
    public function setDeleteAt($deleteAt)
    {
        $this->deleteAt = $deleteAt;
    }


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */

    private $created;

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }


    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @Gedmo\Timestampable(on="change", field={"titre"})
     * @ORM\Column(name="titleChanged", type="datetime", nullable=true)
     */
    private $titleChanged;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;
    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set titre
     *
     * @param string $titre
     * @return Pages
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
    }
    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }
    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Pages
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
        return $this;
    }
    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }
}
