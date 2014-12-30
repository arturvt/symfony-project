<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 9/29/14
 * Time: 6:41 PM
 */

namespace Blog\BaseCRUDBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BaseEntity
 *
 * @package Blog\BaseCRUDBundle\Entity
 */
class BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
}
