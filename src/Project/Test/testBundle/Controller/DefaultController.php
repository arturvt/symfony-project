<?php

namespace Project\Test\testBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($limit)
    {
        $number = rand(1,$limit);

        return $this->render(
            'ProjectTesttestBundle:Random:index.html.twig',
            array('number' => $number)
        );
    }

    public function aboutAction()
    {
        return new Response(
            '<html><head>',
        '    <title>About Section!</title>',
        '</head><body>',
            '<h1>About SECTION!</h1>',
        '</body></html>'
        );
    }
}
