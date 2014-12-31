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
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @return User
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


    public function getUserJsonAction($id)
    {
//        $user = $this->getUserEntity($id);
        // Create a top level instance somewhere
        $fractal = new Manager();
//
//        $userJson = [
//            'id' => $user->getId(),
//            'name' => $user->getName()
//        ];



        $books = [
            [
                'id' => "1",
                'title' => "Hogfather",
                'yr' => "1998",
                'author_name' => 'Philip K Dick',
                'author_email' => 'philip@example.org',
            ],
            [
                'id' => "2",
                'title' => "Game Of Kill Everyone",
                'yr' => "2014",
                'author_name' => 'George R. R. Satan',
                'author_email' => 'george@example.org',
            ]
        ];

        // Pass this array (collection) into a resource, which will also have a "Transformer"
        // This "Transformer" can be a callback or a new instance of a Transformer object
        // We type hint for array, because each item in the $books var is an array
        $resource = new Collection($books, function (array $book) {
                return [
                    'id'      => (int) $book['id'],
                    'title'   => $book['title'],
                    'year'    => (int) $book['yr'],
                    'author'  => [
                        'name'  => $book['author_name'],
                        'email' => $book['author_email'],
                    ],
                    'links'   => [
                        [
                            'rel' => 'self',
                            'uri' => '/books/1',
                        ]
                    ]
                ];
        });
        $response = new Response($fractal->createData($resource)->toJson());
        $response->headers->set('Content-Type', 'application/json');

        return $response;
//        return new Response($fractal->createData($resource)->toJson());
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
        return $this->render('BlogAPIBundle:Default:index.html.twig', array('name' => ($user->getName())));
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
        return new Response('<h1>Found this user: '.$user->getName().'</h1>');
        // ... do something, like pass the $product object into a template
    }
}