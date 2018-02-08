<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 * @ORM\Table(name="produit")
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route("app_produit_show",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *     href = @Hateoas\Route("app_produit_create",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "modify",
 *     href = @Hateoas\Route("app_produit_update",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href = @Hateoas\Route("app_produit_delete",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 *
 */
class Produit
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Expose()
     * @Assert\NotBlank()
     * @Serializer\Since("1.0")
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose()
     * @Assert\NotBlank()
     * @Serializer\Since("1.0")
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Serializer\Expose()
     * @Serializer\Since("2.0")
     */
    private $marque;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @Assert\NotBlank()
     * @Serializer\Expose()
     * @Serializer\Since("2.0")
     */
    private $price;


    public function getId()
    {
        return $this->id;
    }



    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Produit
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Produit
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
     * Set marque
     *
     * @param string $marque
     *
     * @return Produit
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return string
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Produit
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }
}
