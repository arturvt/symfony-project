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
use Symfony\Component\Translation\Exception\NotFoundResourceException;

//This is for Route annotation
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController
 * This class provide all actions related to users.
 * @package Blog\APIBundle\Controller
 */
class UserController extends BaseCRUDController
{

    /**
     * Gets user Entity by a given ID
     *
     * @param $id
     * @throws NotFoundResourceException
     * @return object
     */
    private function getUserEntity($id)
    {
        $user = $this->getDoctrine()
            ->getRepository('BlogAPIBundle:User')
            ->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'No User found for id '.$id
            );
        }
        return $user;
    }


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

    /**
     * @Route("/user-html/{id}", name="blog_api_users_get_html")
     * @param $id
     * @return Response
     */
    public function getWithHTMLAction($id)
    {
        $user = $this->getUserEntity($id);
        return $this->render('BlogAPIBundle:Default:index.html.twig', array('name' => $user->getName()));
    }

    public function createAction()
    {
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setName("User: ".$i);
            $em->persist($user);
        }
        $em->flush();
        return new Response('Added: '.$i.' users.');
    }

    /**
     * Shows user Info.
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $user = $this->getUserEntity($id);
        return new Response('Found this user: '.$user->getName());
        // ... do something, like pass the $product object into a template
    }
}