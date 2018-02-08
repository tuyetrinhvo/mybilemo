<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Produit;
use AppBundle\Exception\ResourceValidationException;
use AppBundle\Representation\Products;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;

class ProductController extends FOSRestController
{
    /**
     * @Rest\Get("/produits", name="app_product_list")
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
     *     section="Products",
     *     resource=true,
     *     description="Get the list of all products"
     * )
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository('AppBundle:Product')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Products($pager);
    }

    /**
     * @Rest\Get(
     *     path = "/products/{id}",
     *     name = "app_product_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View
     * @Doc\ApiDoc(
     *     section="Products",
     *     resource=true,
     *     description="Get one product",
     *     requirements={
     *     {
     *        "name"="id",
     *        "dataType"="integer",
     *        "requirements"="\d+",
     *        "description"="The product unique identifier."
     *     }
     *   }
     * )
     */
    public function showAction(Product $product)
    {
        return $product;
    }

    /**
     * @Rest\Post("/products")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("product", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
     *     section="Products",
     *     resource=true,
     *     description="Create an product",
     *     statusCodes={
     *        201="Returned when created",
     *        400="Returned when a violation is raised by validation"
     *     }
     * )
     */
    public function createAction(Product $product, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();

        return $product;
    }

    /**
     * @Rest\View(StatusCode = 200)
     * @Rest\Put(
     *     path = "/products/{id}",
     *     name = "app_product_update",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("newproduct", converter="fos_rest.request_body")
     */
    public function updateAction(Product $product, Product $newproduct, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $product->setTitle($newproduct->getTitle());
        $product->setContent($newproduct->getContent());

        $this->getDoctrine()->getManager()->flush();

        return $product;
    }

    /**
     * @Rest\View(StatusCode = 204)
     * @Rest\Delete(
     *     path = "/products/{id}",
     *     name = "app_product_delete",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function deleteAction(Product $product)
    {
        $this->getDoctrine()->getManager()->remove($product);

        return;
    }


}
