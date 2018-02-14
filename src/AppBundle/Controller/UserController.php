<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Representation\UserRepresentation;
use AppBundle\Service\ClientManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Exception\ResourceValidationException;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\Validator\ConstraintViolationList;


class UserController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/users",
     *     name = "app_user_list")
     *
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
     *		section = "Users",
     *		resource = true,
     *		description = "Get all users registered",
     *      statusCodes={
     *         200="Returned when request is successful",
     *         401="Returned when the user is not authorized",
     *         404="Returned when request content is not found"
     *     }
     * )
     */
    public function listUserAction(ParamFetcherInterface $paramFetcher)
    {

        $pager = $this->getDoctrine()->getRepository('AppBundle:User')->search(
                $paramFetcher->get('order'),
                $paramFetcher->get('limit'),
                $paramFetcher->get('offset'),
                $paramFetcher->get('keyword')
            );

            return new UserRepresentation($pager);

    }

    /**
     * @Rest\Get(
     *     path = "/users/{id}",
     *     name = "app_user_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     *
     * @Doc\ApiDoc(
     * 		section = "Users",
     * 		resource = true,
     *		description = "Get one user",
     *		requirements={
     * 			{
     *				"name"="id",
     *				"dataType"="integer",
     *				"requirement"="\d+",
     *				"description"="To show a user, on Postman make GET with path = '/users/{id}' "
     * 			}
     *		},
     *      statusCodes={
     *         200="Returned when request is successful",
     *         401="Returned when the user is not authorized",
     *         404="Returned when request content is not found"
     *     }
     * )
     */
    public function showUserAction(User $user)
    {
        return $user;
    }

    /**
     * @Rest\Post(
     *    path = "/users",
     *    name = "app_user_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @Doc\ApiDoc(
     *		section = "Users",
     *		resource = true,
     *		description = "Add a new user",
     *      requirements={
     * 			{
     *				"name"="array",
     *				"dataType"="Json",
     *              "description"= "To create a new user, on Postman make POST with path = '/users' with these datas: 'username' = 'your username', 'email' = 'your email', 'password' = 'your password' "
     * 			}
     *		},
     *      statusCodes={
     *         201="Returned when created",
     *         400="Returned when a violation is raised by validation",
     *         401="Returned when the user is not authorized"
     *     }
     * )
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

        $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $user->setEnabled(true);
        $user->setRoles(['ROLE_ADMIN']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $client = $this->get(ClientManager::class)->createClient($this->getParameter('redirect_uri'));

        return[
            'user' => $user,
            'client' =>[
                    'client_id' => $client->getPublicId(),
                    'client_secret' => $client->getSecret()
            ]
        ];

     }


    /**
     * @Rest\Put(
     *     path = "/users/{id}",
     *     name = "app_user_update",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("newUser", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
     *		section="Users",
     *		resource=true,
     *		description="Modify a user",
     *		requirements={
     * 			{
     *				"name"="array",
     *				"dataType"="Json",
     *              "description"= "To modify a user, on Postman make PUT with path = '/users/{id}' with these datas: 'username' = 'your new username', or 'email' = 'your new email', or 'password' = 'your new password' "
     * 			}
     *		},
     *
     *      statusCodes={
     *         201="Returned when modified",
     *         401="Returned when the user is not authorized",
     *         404="Returned when request content is not found"
     *     }
     * )
     */
    public function updateUserAction(User $user, User $newUser, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $user->setUsername($newUser->getUsername());
        $password = $this->get('security.password_encoder')->encodePassword($newUser, $newUser->getPassword());
        $user->setPassword($password);
        $user->setEmail($newUser->getEmail());

        $this->getDoctrine()->getManager()->flush();

        return $user;
    }

    /**
     * @Rest\Delete(
     *     path = "/users/{id}",
     *     name = "app_user_delete",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 204)
     *
     * @Doc\ApiDoc(
     *		section="Users",
     *		resource=true,
     *		description="Delete a user",
     *		requirements={
     * 			{
     *				"name"="id",
     *				"dataType"="integer",
     *				"requirement"="\d+",
     *              "description"= "To delete a user, on Postman make DELETE with path = '/users/{id}' "
     * 			}
     *		},
     *      statusCodes={
     *         204="Returned when deleted",
     *         401="Returned when the user is not authorized",
     *         404="Returned when request content is not found"
     *     }
     * )
     */
    public function deleteUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return;
    }

}