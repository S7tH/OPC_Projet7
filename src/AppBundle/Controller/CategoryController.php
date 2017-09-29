<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Category;

/**
* @Route("/api")
*/
class CategoryController extends Controller
{
	/**
	 * @Rest\Get(
	 *		path = "/categories",
	 * 		name = "category_list"
	 * )
	 * @Rest\View
	 */
	public function listCategoryAction()
	{
		$categories = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category')->findAll();

		return $categories;
	}


	/**
	 * @Rest\Get(
	 *		path = "/categories/{id}",
	 *		name = "get_category",
	 *		requirements = {"id"="\d+"}
	 * )
	 * @Rest\View
	 */
	public function getCategoryAction(Category $category)
	{
		return $category;
	}

}
