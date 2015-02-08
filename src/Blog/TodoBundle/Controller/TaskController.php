<?php

namespace Blog\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{

    /**
     * Returns all tasks from logged User
     */
    public function getTasksAction()
    {
        $response = new Response(json_encode(["msg" => "should show tasks"]));
        return $response;
    }

}
