<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;

/**
 * RefreshToken
 *
 * @ORM\Table(name="refresh_token")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RefreshTokenRepository")
 */
class RefreshToken extends BaseRefreshToken
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
     * @var string
     *
     * @ORM\Column(name="client", type="string", length=255)
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    protected $user;

}

