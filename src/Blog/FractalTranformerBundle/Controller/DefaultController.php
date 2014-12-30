<?php

namespace Blog\FractalTranformerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BlogFractalTranformerBundle:Default:index.html.twig', array('name' => $name));
    }
}
