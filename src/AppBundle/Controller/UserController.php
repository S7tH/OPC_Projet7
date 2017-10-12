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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Hateoas\Representation\PaginatedRepresentation;

use Nelmio\ApiDocBundle\Annotation as Doc;


/**
 * @Route("/api")
 */
class UserController extends FOSRestController
{

	/**
	 * @Rest\Get(
	 *		path = "/user",
	 *		name = "app_user_list"
	 * )
	 * @Rest\QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
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
	 * @Rest\View
	 *
	 * @Doc\ApiDoc(
	 *		section = "User",
	 *		resource = true,
	 *		description = "Get all users registered."
	 * )
	 */
	public function getUsersAction(ParamFetcherInterface $paramFetcher)
	{

		$pager = $this->getDoctrine()->getRepository('AppBundle:User')->search(
			$paramFetcher->get('keyword'),
			$paramFetcher->get('order'),
			$paramFetcher->get('limit'),
			$paramFetcher->get('offset')
		);

		return new UserRepresentation($pager);
	}

	/**
	 * @Rest\Get(
	 *		path = "/user/{id}",
	 *		name = "app_user_show",
	 *		requirements = {"id"="\d+"}
	 * )
	 *
	 * @Rest\View
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
	 *		}
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
     */
     public function createUserAction(User $user, ConstraintViolationList $violations)
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

    /**
     * @Rest\View(StatusCode = 200)
     * @Rest\Put(
     *     path = "/user/{id}",
     *     name = "app_user_update",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("newUser", converter="fos_rest.request_body")
     */
     public function updateAction(User $user, User $newUser, ConstraintViolationList $violations)
     {
         if (count($violations)) {
             $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
             foreach ($violations as $violation) {
                 $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
             }
 
             throw new ResourceValidationException($message);
         }
 
         $user->setTitle($newUser->getTitle());
         $user->setContent($newUser->getContent());
 
         $this->getDoctrine()->getManager()->flush();
 
         return $user;
     }

    /**
     * @Rest\View(StatusCode = 204)
     * @Rest\Delete(
     *     path = "/user/{id}",
     *     name = "app_user_delete",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function deleteAction(User $user)
    {
        $this->getDoctrine()->getManager()->remove($user)->flush();

        return;
    }

}
