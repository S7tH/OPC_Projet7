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

use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/api")
 */
class ProducesController extends FOSRestController
{
    /**
     * @Rest\Get("/produces", name="app_produces_list")
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
	 *		section="Produces",
	 * 		resource=true,
     *		description="Get the list of all produces.",
     *      statusCodes={
     *         200="Returned when request is successful",
     *         401="Returned when the produce is not authorized",
     *         404="Returned when request content is not found"
     *     }
	 * )
     */
     public function listAction(ParamFetcherInterface $paramFetcher)
     {
        if($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            $pager = $this->getDoctrine()->getRepository('AppBundle:Produces')->search(
                $paramFetcher->get('order'),
                $paramFetcher->get('limit'),
                $paramFetcher->get('offset'),
                $paramFetcher->get('keyword')
        );
    
            return new ProducesRepresentation($pager);
        }
        else
        { 
            throw new AccessDeniedException('You must be registered as a Bilemo customer to access the content. Please contact bilemo if you want to become a customer');
        }
        
     }

    
    /**
     * @Rest\Get(
     *     path = "/produces/{id}",
     *     name = "app_produces_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
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
	 *		},
     *      statusCodes={
     *         200="Returned when request is successful",
     *         401="Returned when the produce is not authorized",
     *         404="Returned when request content is not found"
     *     }
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
     *
     * @Doc\ApiDoc(
	 *		section="Produces",
	 *		resource=true,
	 *		description="Add a new produce.",
	 *		requirements={
	 * 			{
	 *				"name"="array",
	 *				"dataType"="Json",
	 *				"requirement"="\d+",
	 *				"description"="To create a new produce, make an array with these datas: 'title' = 'your title', 'content' = 'its fulltext', 'short_description' = 'its short description'"
     * 			}
	 *		},
     *      statusCodes={
     *         201="Returned when created",
     *         401="Returned when the produce is not authorized",
     *         404="Returned when request content is not found"
     *     }
	 * )
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
     }

    /**
     * @Rest\View(StatusCode = 201)
     * @Rest\Put(
     *     path = "/produces/{id}",
     *     name = "app_produces_update",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("newProduces", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
	 *		section="Produces",
	 *		resource=true,
	 *		description="Modify a produce.",
	  *		requirements={
	 * 			{
	 *				"name"="id",
	 *				"dataType"="integer",
	 *				"requirement"="\d+",
	 *				"description"="The produce unique identifier.  Show how to create a produce to know the keys what you can change"
     * 			}
	 *		},
     *      statusCodes={
     *         201="Returned when modified",
     *         401="Returned when the produce is not authorized",
     *         404="Returned when request content is not found"
     *     }
	 * )
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
     *
     * @Doc\ApiDoc(
	 *		section="Produces",
	 *		resource=true,
	 *		description="Delete a produce.",
	  *		requirements={
	 * 			{
	 *				"name"="id",
	 *				"dataType"="integer",
	 *				"requirement"="\d+",
	 *				"description"="The produce unique identifier."
     * 			}
	 *		},
     *      statusCodes={
     *         204="Returned when deleted",
     *         401="Returned when the produce is not authorized",
     *         404="Returned when request content is not found"
     *     }
	 * )
     */
    public function deleteAction(Produces $produces)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($produces);
        
        $em->flush();

        return;
    }
}

