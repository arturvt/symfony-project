<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 10/2/14
 * Time: 6:49 PM
 */

namespace Blog\APIBundle\Controller;


use Blog\APIBundle\Entity\User;
use Blog\BaseCRUDBundle\Controller\BaseCRUDController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * This class provide all actions related to users.
 * @package Blog\APIBundle\Controller
 */
class UserController extends BaseCRUDController
{
    /**
     * Return Info about users
     *
     * @Rest\View
     *
     * @ApiDoc(
     *      section="BaseEntity",
     *      description="This method should return all information related to all users."
     * )
     *
     * @return Response
     */
    public function getUsersInfoAction()
    {
        return new Response('<h1>Here are all users....</h1>');
    }

    public function createAction()
    {
        $user = new User();
        $user->setName("Artur - AA");
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new Response('User created. ID: '.$user->getId());
    }
}