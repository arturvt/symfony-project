<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 10/2/14
 * Time: 7:16 PM
 */

namespace Blog\APIBundle\Service;


use Blog\BaseCRUDBundle\service\BaseCRUDService;

class UserService extends BaseCRUDService
{
    //TODO - Implement all services for users.
    /**
     * @return array with all users
     */
    public function getAll()
    {
        $users = $this->em->getRepository($this->entityFullName)->findAll();
        return $users;
    }
}
