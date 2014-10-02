<?php

namespace Blog\BaseCRUDBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BlogBaseCRUDBundle:Default:index.html.twig', array('name' => $name));
    }
}
