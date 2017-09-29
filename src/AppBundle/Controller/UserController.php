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


/**
 * @Route("/api")
 */
class UserController extends FOSRestController
{
	/**
	 * @Rest\Get(
	 *		path = "/user-check/{username}",
	 *		name = "get_user_check"
	 * )
	 *
	 * @Rest\View
	 */
	 public function getUserCheckAction(User $user)//Request $request)
	 {
		//$users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();
		
		 return $user;
	 }

	/**
	 * @Rest\Get(
	 *		path = "/user",
	 *		name = "app_user_list"
	 * )
	 *
	 * @Rest\View
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
	 */
	public function getUserAction(User $user)
	{
		return $user;
	}
}
