<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\Produces;
use AppBundle\Exception\ResourceValidationException;
use AppBundle\Representation\ProducesRepresentation;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\HttpFoundation\Response;

use Hateoas\Representation\PaginatedRepresentation;

use Nelmio\ApiDocBundle\Annotation as Doc;


/**
 * @Route("/api")
 */
class ProducesController extends FOSRestController
{
    /**
     * @Rest\Get("/produces", name="app_produces_list")
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
     * @Rest\View()
     *
     * @Doc\ApiDoc(
	 *		section="Produces",
	 * 		resource=true,
	 *		description="Get the list of all productes."
	 * )
     */
     public function listAction(ParamFetcherInterface $paramFetcher)
     {
         $pager = $this->getDoctrine()->getRepository('AppBundle:Produces')->search(
             $paramFetcher->get('keyword'),
             $paramFetcher->get('order'),
             $paramFetcher->get('limit'),
             $paramFetcher->get('offset')
         );
 
         return new ProducesRepresentation($pager);
     }

    
    /**
     * @Rest\Get(
     *     path = "/produces/{id}",
     *     name = "app_produces_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View
     *
     * @Doc\ApiDoc(
	 *		section="Produces",
	 *		resource=true,
	 *		description="Get one produce.",
	 *		requirements={
	 * 			{
	 *				"name"="id",
	 *				"dataType"="integer",
	 *				"requirement"="\d+",
	 *				"description"="The produce unique identifier."
	 * 			}
	 *		}
	 * )
     */
     public function showAction(Produces $produces)
     {
         return $produces;
     }

    /**
     * @Rest\Post(
     *    path = "/produces",
     *    name = "app_produces_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("produces", converter="fos_rest.request_body")
     */
     public function createAction(Produces $produces, ConstraintViolationList $violations)
     {
         if (count($violations)) {
             $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
             foreach ($violations as $violation) {
                 $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
             }
 
             throw new ResourceValidationException($message);
         }
 
         $em = $this->getDoctrine()->getManager();
 
         $em->persist($produces);
         $em->flush();
 
         return $produces;
         /*return $this->view($produces, Response::HTTP_CREATED, ['Location' => $this->generateUrl('app_produces_show',
         ['id' => $produces->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);*/
     }

    /**
     * @Rest\View(StatusCode = 200)
     * @Rest\Put(
     *     path = "/produces/{id}",
     *     name = "app_produces_update",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("newProduces", converter="fos_rest.request_body")
     */
     public function updateAction(Produces $produces, Produces $newProduces, ConstraintViolationList $violations)
     {
         if (count($violations)) {
             $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
             foreach ($violations as $violation) {
                 $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
             }
 
             throw new ResourceValidationException($message);
         }
 
         $produces->setTitle($newProduces->getTitle());
         $produces->setContent($newProduces->getContent());
 
         $this->getDoctrine()->getManager()->flush();
 
         return $produces;
     }

    /**
     * @Rest\View(StatusCode = 204)
     * @Rest\Delete(
     *     path = "/produces/{id}",
     *     name = "app_produces_delete",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function deleteAction(Produces $produces)
    {
        $this->getDoctrine()->getManager()->remove($produces)->flush();

        return;
    }
}

