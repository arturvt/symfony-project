<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 8/27/14
 * Time: 5:07 PM
 */
namespace Acme\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class MainController
{
    public function contactAction()
    {
        return new Response('<h1>Contact Us!!!</h1>');
    }
}