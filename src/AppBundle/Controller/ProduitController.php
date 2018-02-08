<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Produit;
use AppBundle\Exception\ResourceValidationException;
use AppBundle\Representation\Produits;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;

class ProduitController extends FOSRestController
{
    /**
     * @Rest\Get("/produits", name="app_produit_list")
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
     *     section="Produits",
     *     resource=true,
     *     description="Get the list of all produits"
     * )
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository('AppBundle:Produit')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Produits($pager);
    }

    /**
     * @Rest\Get(
     *     path = "/produits/{id}",
     *     name = "app_produit_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View
     * @Doc\ApiDoc(
     *     section="Produits",
     *     resource=true,
     *     description="Get one produit",
     *     requirements={
     *     {
     *        "name"="id",
     *        "dataType"="integer",
     *        "requirements"="\d+",
     *        "description"="The produit unique identifier."
     *     }
     *   }
     * )
     */
    public function showAction(Produit $produit)
    {
        return $produit;
    }

    /**
     * @Rest\Post("/produits")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("produit", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
     *     section="Produits",
     *     resource=true,
     *     description="Create an produit",
     *     statusCodes={
     *        201="Returned when created",
     *        400="Returned when a violation is raised by validation"
     *     }
     * )
     */
    public function createAction(Produit $produit, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($produit);
        $em->flush();

        return $produit;
    }

    /**
     * @Rest\View(StatusCode = 200)
     * @Rest\Put(
     *     path = "/produits/{id}",
     *     name = "app_produit_update",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("newproduit", converter="fos_rest.request_body")
     */
    public function updateAction(Produit $produit, Produit $newproduit, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $produit->setTitle($newproduit->getTitle());
        $produit->setContent($newproduit->getContent());

        $this->getDoctrine()->getManager()->flush();

        return $produit;
    }

    /**
     * @Rest\View(StatusCode = 204)
     * @Rest\Delete(
     *     path = "/produits/{id}",
     *     name = "app_produit_delete",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function deleteAction(Produit $produit)
    {
        $this->getDoctrine()->getManager()->remove($produit);

        return;
    }


}
