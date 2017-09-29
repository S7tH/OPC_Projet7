<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Produces
 *
 * @ORM\Table(name="produces")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProducesRepository")
 *
 * @Serializer\ExclusionPolicy("ALL")
 *
 * @Hateoas\Relation("self",
 * href= @Hateoas\Route("app_produces_show",
 * parameters = { "id" = "expr(object.getId())"},
 * absolute = true
 * ))
 *
 * @Hateoas\Relation("modify",
 * href = @Hateoas\Route("app_produces_update",
 * parameters = { "id" = "expr(object.getId())" },
 * absolute = true
 * ))
 *
* @Hateoas\Relation("delete",
 * href = @Hateoas\Route("app_produces_delete",
 * parameters = { "id" = "expr(object.getId())" },
 * absolute = true
 * ))
 *
 * @Hateoas\Relation("authenticated_user",
 * embedded = @Hateoas\Embedded("expr(service('security.token_storage').getToken().getUser())")
 * )
 */
class Produces
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Since("1.0")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     *
     * @Assert\NotBlank(groups={"Create"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=255)
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     *
     * @Assert\NotBlank(groups={"Create"})
     */
    private $content;

    /**
    * @var string
    *
    * @ORM\Column(name="shortdescription", type="text", length=255, nullable=true)
    *
    * @Serializer\Expose
    * @Serializer\Since("2.0")
    */
    private $shortDescription;

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
     * Set title
     *
     * @param string $title
     *
     * @return Produces
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Produces
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return Produces
     */
     public function setShortDescription($shortDescription)
     {
         $this->shortDescription = $shortDescription;
 
         return $this;
     }
 
     /**
      * Get shortDescription
      *
      * @return string
      */
     public function getShortDescription()
     {
         return $this->shortDescription;
     }
}
