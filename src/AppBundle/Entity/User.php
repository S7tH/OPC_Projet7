<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 * @ExclusionPolicy("all")
 *
 */
class User implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=255, unique=true, nullable=true)
     *
     * @Expose
     */
    private $username;
    
    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, unique=true, nullable=true)
     *
     * @Expose
     */
    private $facebook_id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Expose
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $gender;

    public function __construct($username, $facebook_id, $email, $gender)
    {
        $this->username = $username;
        $this->facebook_id = $facebook_id;
        $this->email = $email;
        $this->gender = $gender;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
     return $this->id;
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function getFacebook_id()
    {
        return $this->facebook_id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getCover()
    {
        return $this->cover;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}