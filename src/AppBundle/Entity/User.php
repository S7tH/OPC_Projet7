<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 * @Serializer\ExclusionPolicy("ALL")
 *
 * @Hateoas\Relation("self",
 *      href = "expr('http://127.0.0.1:8001/api/user/' ~ object.getId())"
 * )
 *
 * @Hateoas\Relation("create",
 *      href = @Hateoas\Route("app_user_create",
 *      absolute = true
 * ))
 *
 * @Hateoas\Relation("modify",
 *      href = "expr('http://127.0.0.1:8001/api/user/' ~ object.getId())"
 * )
 *
 * @Hateoas\Relation("delete",
 *      href = "expr('http://127.0.0.1:8001/api/user/' ~ object.getId())"
 * )
 *
 * @Hateoas\Relation("authenticated_user",
 * embedded = @Hateoas\Embedded("expr(service('security.token_storage').getToken().getUser())")
 * )
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
     private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true, nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $username;
    
    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, unique=true, nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $gender;

    /**
     * @ORM\Column(name="roles", type="array")
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     */
    private $roles = array();

    public function __construct($username, $facebookId, $email, $gender)
    {
        $this->username = $username;
        $this->facebookId = $facebookId;
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

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
     public function setUsername($username)
     {
         $this->username = $username;
 
         return $this;
     }

    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
         $this->facebookId = $facebookId;
 
         return $this;
    }

    
    public function getEmail()
    {
        return $this->email;
    }

    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
     public function setGender($gender)
     {
         $this->gender = $gender;
 
         return $this;
     }


    public function getRoles()
    {
        return $this->roles;
    }

     /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
     public function setRoles($roles)
     {
         $this->roles = $roles;
 
         return $this;
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

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

}
