<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Un compte existe déjà avec cet email")
 *
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *      "list user",
 *      href = @Hateoas\Route("app_user_list",
 *          absolute = true
 *     )
 * )
 *
 * @Hateoas\Relation(
 *      "show user",
 *      href = @Hateoas\Route("app_user_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "create user",
 *     href = @Hateoas\Route("app_user_create",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "modify user",
 *     href = @Hateoas\Route("app_user_update",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete user",
 *     href = @Hateoas\Route("app_user_delete",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 *
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     */
    protected $username;

    /**
     * @Assert\NotBlank()
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     */
    protected $password;

    /**
     * @ORM\Column(unique=true)
     * @Assert\NotBlank()
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     */
    protected $email;

}

