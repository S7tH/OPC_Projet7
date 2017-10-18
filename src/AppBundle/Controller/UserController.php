<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\User;
use AppBundle\Exception\ResourceValidationException;
use AppBundle\Representation\UserRepresentation;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\HttpFoundation\Response;

use Hateoas\Representation\PaginatedRepresentation;

use Nelmio\ApiDocBundle\Annotation as Doc;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;




/**
 * @Route("/api")
 */
class UserController extends FOSRestController
{

	/**
	 * @Rest\Get("/user",name = "app_user_list")
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of movies per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="The pagination offset"
     * )
     * @Rest\QueryParam(
     *     name="keyword",
     *     nullable=true,
     *     requirements="[a-zA-Z0-9]+",
     *     description="The keyword to search for."
     * )
     * @Rest\View(StatusCode = 200)
     *
	 * @Doc\ApiDoc(
	 *		section = "User",
	 *		resource = true,
     *		description = "Get all users registered.",
     *      statusCodes={
     *         200="Returned when request is successful",
     *         401="Returned when the user is not authorized",
     *         404="Returned when request content is not found"
     *     }
     * )
	 */
	public function getUsersAction(ParamFetcherInterface $paramFetcher)
	{
        if($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
		    $pager = $this->getDoctrine()->getRepository('AppBundle:User')->search(
			    $paramFetcher->get('order'),
			    $paramFetcher->get('limit'),
                $paramFetcher->get('offset'),
                $paramFetcher->get('keyword')
		    );

            return new UserRepresentation($pager);
        }
        else
        { 
            throw new AccessDeniedException('You must be registered as a Bilemo customer to access the content. Please contact bilemo if you want to become a customer');
        }
	}

	/**
	 * @Rest\Get(
	 *     path = "/user/{id}",
	 *     name = "app_user_show",
	 *     requirements = {"id"="\d+"}
	 * )
     * @Rest\View(StatusCode = 200)
	 *
	 * @Doc\ApiDoc(
	 * 		section = "User",
	 * 		resource = true,
	 *		description = "Get one user.",
	 *		requirements={
	 * 			{
	 *				"name"="id",
	 *				"dataType"="integer",
	 *				"requirement"="\d+",
	 *				"description"="The user unique identifier."
	 * 			}
     *		},
     *      statusCodes={
     *         200="Returned when request is successful",
     *         401="Returned when the user is not authorized",
     *         404="Returned when request content is not found"
     *     }
     * )
	 */
	public function getUserAction(User $user)
	{
		return $user;
	}

	/**
     * @Rest\Post(
     *    path = "/user",
     *    name = "app_user_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @Doc\ApiDoc(
	 *		section = "User",
	 *		resource = true,
     *		description = "Add a new user.",
     *      requirements={
	 * 			{
	 *				"name"="array",
     *				"dataType"="Json",
     *              "description"= "To create a new user, make an array with these datas: 'username' = null, 'facebook_id' = null, 'email' = 'user_facebook_account_email', 'gender' = null"
     * 			}
     *		},
     *      statusCodes={
     *         201="Returned when created",
     *         400="Returned when a violation is raised by validation",
     *         401="Returned when the user is not authorized"
     *     }
	 * )
     */
     public function createUserAction(User $user, ConstraintViolationList $violations)
     {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            if (count($violations)) {
                $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
                foreach ($violations as $violation) {
                    $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
                }
    
                throw new ResourceValidationException($message);
            }
    
            $em = $this->getDoctrine()->getManager();
    
            $em->persist($user);
            $em->flush();
    
            return $user;
        }
        else
        {
            throw new AccessDeniedException('Only an administrator can validate the registration.');
        }
     }

    /**
     * @Rest\Put(
     *     path = "/user/{id}",
     *     name = "app_user_update",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("newUser", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
	 *		section="User",
	 *		resource=true,
	 *		description="Modify a user.",
	  *		requirements={
	 * 			{
	 *				"name"="id",
	 *				"dataType"="integer",
	 *				"requirement"="\d+",
	 *				"description"="The user unique identifier. Show how to create a user to know the keys what you can change"
     * 			}
	 *		},
     *      statusCodes={
     *         201="Returned when modified",
     *         401="Returned when the user is not authorized",
     *         404="Returned when request content is not found"
     *     }
	 * )
     */
     public function updateUserAction(User $user, User $newUser, ConstraintViolationList $violations)
     {
         if (count($violations)) {
             $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
             foreach ($violations as $violation) {
                 $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
             }
 
             throw new ResourceValidationException($message);
         }
 
         $user->setUsername($newUser->getUsername());
         $user->setEmail($newUser->getEmail());
 
         $this->getDoctrine()->getManager()->flush();
 
         return $user;
     }

    /**
     * @Rest\Delete(
     *     path = "/user/{id}",
     *     name = "app_user_delete",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 204)
     *
     * @Doc\ApiDoc(
	 *		section="User",
	 *		resource=true,
	 *		description="Delete a user.",
	  *		requirements={
	 * 			{
	 *				"name"="id",
	 *				"dataType"="integer",
	 *				"requirement"="\d+",
	 *				"description"="The user unique identifier."
     * 			}
	 *		},
     *      statusCodes={
     *         204="Returned when deleted",
     *         401="Returned when the user is not authorized",
     *         404="Returned when request content is not found"
     *     }
	 * )
     */
    public function deleteUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($user);

        $em->flush();

        return;
    }

}
