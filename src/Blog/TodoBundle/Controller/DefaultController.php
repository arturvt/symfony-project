<?php

namespace Blog\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BlogTodoBundle:Default:index.html.twig', array('name' => $name));
    }
}
