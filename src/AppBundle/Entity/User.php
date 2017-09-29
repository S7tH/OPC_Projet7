<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Model\User as BaseUser;

use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * User
 *
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 * @Serializer\ExclusionPolicy("ALL")
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_user_show",
 *          parameters = { "id" = "expr(object.getId())"},
 *          absolute = true
 *      )
 * )
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
     * @var string
     * @Serializer\Expose
     */
     protected $username;
    
    /**
     * @var string
     * @Serializer\Expose
     */
    protected $email;

    /**
     * @var \DateTime
     * @Serializer\Expose
     */
    protected $lastLogin;

    /**
     * User constructor.
     */
     public function __construct()
     {
         $this->enabled = false;

         if($this->roles === null)
         {
            $this->roles = ['ROLE_USER'];
         }
         else
         {
            $this->roles = array();
         }
     }
}
