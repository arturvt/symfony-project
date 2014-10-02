<?php

namespace Acme\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcmeBlogBundle:Default:index.html.twig');
    }

    public function helloAction($name)
    {
        return $this->render('AcmeBlogBundle:Default:user.html.twig', array('name' => $name));
    }

    public function listUsersAction()
    {
        return $this->render('AcmeBlogBundle:Default:list.html.twig');
    }

    public function getUserAction($id)
    {
        return new Response('<html><body><h1>User: '.$id.'</h1></body></html>');
    }
}
