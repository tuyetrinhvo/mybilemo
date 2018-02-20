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
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer Token",
     *             "required"="true"
     *         }
     *     },
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
     *              "requirement"="\d+",
     *              "description"="The product unique identifier. To show a product, on Postman make GET with path = '/products/{id}'"
     *          }
     *      },
     *      headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer Token",
     *             "required"="true"
     *         }
     *     },
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
     *				"dataType"="string",
     *              "requirement"="application/json",
     *              "description"="To create a new product, on Postman make POST with path = '/products' with these datas: 'name', 'description', 'brand', and 'price' "
     * 			}
     *		},
     *      headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer Token",
     *             "required"="true"
     *         }
     *     },
     *      statusCodes={
     *         201="Returned when created",
     *         401="Returned when the product is not authorized",
     *         404="Returned when request content is not found"
     *     },
     *     parameters={
     *          {"name"="name", "dataType"="string", "required"=true, "description"="name of product"},
     *          {"name"="description", "dataType"="string", "required"=true, "description"="description of product"},
     *          {"name"="brand", "dataType"="string", "required"=true, "description"="brand of product"},
     *          {"name"="price", "dataType"="string", "required"=true, "description"="price of product"}
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
     *				"name"="array",
     *				"dataType"="string",
     *              "requirement"="application/json",
     *              "description"="To modify a product, on Postman make POST with path = '/products/{id}' with these datas: 'name', 'description', 'brand', and 'price' "
     * 			},
     *          {
     *				"name"="id",
     *				"dataType"="integer",
     *				"requirement"="\d+",
     *				"description"="The product unique identifier"
     * 			}
     *		},
     *      headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer Token",
     *             "required"="true"
     *         }
     *     },
     *      statusCodes={
     *         201="Returned when modified",
     *         401="Returned when the product is not authorized",
     *         404="Returned when request content is not found"
     *     },
     *     parameters={
     *          {"name"="name", "dataType"="string", "required"=true, "description"="new name of product"},
     *          {"name"="description", "dataType"="string", "required"=true, "description"="new description of product"},
     *          {"name"="brand", "dataType"="string", "required"=true, "description"="new brand of product"},
     *          {"name"="price", "dataType"="string", "required"=true, "description"="new price of product"}
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
     *		description="Delete a product",
     *		requirements={
     * 			{
     *				"name"="id",
     *				"dataType"="integer",
     *				"requirement"="\d+",
     *				"description"="The product unique identifier"
     * 			}
     *		},
     *      headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer Token",
     *             "required"="true"
     *         }
     *     },
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
