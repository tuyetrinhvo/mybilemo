<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Exception\ResourceValidationException;
use AppBundle\Representation\ProductRepresentation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;

class ProductController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/products",
     *     name="app_product_list"
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
     * @Rest\View(StatusCode = 200)
     *
     * @Doc\ApiDoc(
     *     section="Products",
     *     resource=true,
     *     description="Get the list of all products",
     *     statusCodes={
     *         200="Returned when request is successful",
     *         401="Returned when the product is not authorized",
     *         404="Returned when request content is not found"
     *     }
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

        return new ProductRepresentation($pager);
        }


    /**
     * @Rest\Get(
     *     path = "/products/{id}",
     *     name = "app_product_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     * @Doc\ApiDoc(
     *     section="Products",
     *     resource=true,
     *     description="Get one product",
     *     requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirements"="\d+",
     *              "description"="The product unique identifier."
     *          }
     *      },
     *     statusCodes={
     *         200="Returned when request is successful",
     *         401="Returned when the product is not authorized",
     *         404="Returned when request content is not found"
     *      }
     * )
     */
    public function showAction(Product $product)
    {
        return $product;
    }

    /**
     * @Rest\Post(
     *     path = "/products",
     *     name = "app_product_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("product", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
     *     section="Products",
     *     resource=true,
     *     description="Create a new product",
     *     requirements={
     * 			{
     *				"name"="array",
     *				"dataType"="Json",
     *				"requirement"="\d+",
     *              "description"="The product unique identifier. Show how to create a produce."
     * 			}
     *		},
     *      statusCodes={
     *         201="Returned when created",
     *         401="Returned when the product is not authorized",
     *         404="Returned when request content is not found"
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
     * @Rest\View(StatusCode = 201)
     * @Rest\Put(
     *     path = "/products/{id}",
     *     name = "app_product_update",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("newproduct", converter="fos_rest.request_body")
     * @Doc\ApiDoc(
     *		section="Products",
     *		resource=true,
     *		description="Modify a product",
     *		requirements={
     * 			{
     *				"name"="id",
     *				"dataType"="integer",
     *				"requirement"="\d+",
     *				"description"="The product unique identifier. Show how to update a product"
     * 			}
     *		},
     *      statusCodes={
     *         201="Returned when modified",
     *         401="Returned when the product is not authorized",
     *         404="Returned when request content is not found"
     *     }
     * )
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

        $product->setName($newproduct->getName());
        $product->setDescription($newproduct->getDescription());
        $product->setBrand($newproduct->getBrand());
        $product->setPrice($newproduct->getPrice());

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
     * @Doc\ApiDoc(
     *		section="Products",
     *		resource=true,
     *		description="Delete a product.",
     *		requirements={
     * 			{
     *				"name"="id",
     *				"dataType"="integer",
     *				"requirement"="\d+",
     *				"description"="The product unique identifier."
     * 			}
     *		},
     *      statusCodes={
     *         204="Returned when deleted",
     *         401="Returned when the product is not authorized",
     *         404="Returned when request content is not found"
     *     }
     * )
     */
    public function deleteAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return;
    }


}
