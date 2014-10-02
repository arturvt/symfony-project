<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 9/29/14
 * Time: 6:42 PM
 */

namespace Blog\BaseCRUDBundle\Util;


class ValidationException extends \Exception
{
    /**
     * @var Array
     */
    private $errors;


    /**
     * @param array $formErrors
     */
    public function __construct(array $formErrors)
    {
        $this->errors = $formErrors;
    }

    /**
     * Returns exception error
     *
     * @return Array
     */
    public function getErrors()
    {
        return $this->errors;
    }

} 