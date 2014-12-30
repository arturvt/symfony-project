<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 10/9/14
 * Time: 5:27 PM
 */

namespace Blog\BaseCRUDBundle\Transformer;

use Doctrine\ORM\EntityManager;
use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    public $em;

    public static $resourceKey = [
        'singular' => '',
        'plural' => ''
    ];

    /**
     * Construct
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * List of available includes
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];
}