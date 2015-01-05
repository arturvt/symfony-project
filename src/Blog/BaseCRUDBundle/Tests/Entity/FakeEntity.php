<?php

namespace Blog\BaseCRUDBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Blog\BaseCRUDBundle\Entity\BaseEntity;

/**
 * FakeEntity
 * @package Blog\BaseCRUDBundle\Tests\Entity
 *
 * @ORM\Entity
 * @ORM\Table()
 *
 */
class FakeEntity extends BaseEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max="64")
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max="64")
     * @Assert\NotBlank()
     */
    private $lastName;

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return FakeEntity
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return FakeEntity
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}