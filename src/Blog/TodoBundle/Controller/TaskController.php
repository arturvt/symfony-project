<?php

namespace Blog\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;
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

//        return new View([
//            "msg" => "show be the tasks for the logged user"
//        ]);
    }

}
