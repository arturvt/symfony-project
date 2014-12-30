<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 10/6/14
 * Time: 11:33 AM
 */

namespace Blog\APIBundle\Transformer;

<<<<<<< HEAD
use Blog\APIBundle\Entity\User;
use Blog\BaseCRUDBundle\Transformer\BaseTransformer;

class UserTransformer extends BaseTransformer
{

    public static $resourceKey = [
        'singular' => 'project-category',
        'plural' => 'project-categories'
    ];

    /**
     * Defines the response structure for a ProjectCategory
     *
     * @param $user User;
     *
     * @return array
     */
    public function transform($user)
    {
        echo 'Transformed';
        $response = [
            'id' => $user->getId(),
            'name' => $user->getName(),
        ];

        return $response;
    }
=======

class UserTransformer
{

>>>>>>> refs/heads/master(github)
}