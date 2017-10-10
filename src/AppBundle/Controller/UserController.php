<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
	 *
	 * @Rest\View
	 *
	 * @Doc\ApiDoc(
	 *		section = "User",
	 *		resource = true,
	 *		description = "Get all users registered."
	 * )
	 */
	public function getUsersAction()
	{
		$users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();
		return $users;
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

}
