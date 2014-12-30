<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 10/2/14
 * Time: 6:46 PM
 */

namespace Blog\APIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Blog\BaseCRUDBundle\Entity\BaseEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User extends BaseEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max="64")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}