<?php

namespace Acme\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name, $lastName, $color)
    {
        if (!strcmp($name, "none")) {
            throw $this->createNotFoundException('Invalid page or name.');
        }
        return $this->render('AcmeTestBundle:Default:index.html.twig', array('name' => $name, 'lastName' => $lastName, 'color' => $color));
    }

    public function showAction() {
        return $this->render('AcmeTestBundle:Default:info.html.twig');
    }
}
