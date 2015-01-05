<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 9/29/14
 * Time: 7:15 PM
 */

namespace Blog\BaseCRUDBundle\service;

use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Blog\BaseCRUDBundle\Entity\BaseEntity;
use Blog\BaseCRUDBundle\Util\ValidationException;

class BaseCRUDService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var string
     */
    protected $entityFullName;

    /**
     * @var string
     */
    protected $formClass;

    /**
     * Constructor for BaseEntityService
     *
     * @param EntityManager $entityManager  Injected Doctrine EntityManager
     * @param FormFactory   $formFactory    Injected FormFactory
     * @param string        $formClass      Injected form class name
     * @param string        $entityFullName Injected entity full name
     */
    public function __construct(
        EntityManager $entityManager,
        FormFactory $formFactory,
        $formClass,
        $entityFullName
    ) {
        $this->em = $entityManager;
        $this->formFactory = $formFactory;
        $this->formClass = $formClass;
        $this->entityFullName = $entityFullName;
    }

    /**
     * Gets a resource by Id.
     *
     * @param string $id id of the resource to be searched.
     *
     * @return object
     *
     * @throws \Symfony\Component\Form\Exception\InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getById($id)
    {
        if (($id == null) || (!is_numeric($id))) {
            throw new InvalidArgumentException("Invalid id.");
        }

        $entity = $this->em->getRepository($this->entityFullName)->find($id);
        if (!$entity) {
            throw new NotFoundHttpException("Unable to find resource.");
        }

        return $entity;
    }

    /**
     * Ads a resource
     *
     * @param array $data an array of the parameters from the request
     *
     * @return BaseEntity
     * @throws \Symfony\Component\Form\Exception\InvalidArgumentException
     */
    public function add(array $data)
    {
        if (empty($data)) {
            throw new InvalidArgumentException("Invalid params.");
        }

        $entity = new $this->entityFullName;
        return $this->saveOrUpdate($entity, $data);
    }

    /**
     * Updates a resource.
     *
     * @param string $id           id of the entity to be updated
     * @param array  $data         an array of the parameters from the request
     * @param bool   $clearMissing set the missing data to null
     *
     * @return mixed
     */
    public function update($id, array $data, $clearMissing)
    {
        /** @var BaseEntity $entity */
        $entity = $this->getById($id);

        return $this->saveOrUpdate($entity, $data, $clearMissing);
    }

    /**
     * Deletes a resource.
     *
     * @param string $id id of the entity to be deleted
     *
     * @return mixed
     *
     */
    public function delete($id)
    {
        /** @var BaseEntity $entity */
        $entity = $this->getById($id);

        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * Returns the fully qualified name of the form
     *  class associated with a given entity.
     *
     * @return Form an object form class
     *
     * @throws \DomainException If the associated form class could not be found
     */
    private function getFormClass()
    {
        if (!class_exists($this->formClass)) {
            throw new \DomainException('Entity form class "' . $this->formClass . '" not found.');
        }

        return new $this->formClass();
    }

    /**
     * Performs a full or partial entity updates
     *
     * @param BaseEntity $entity       BaseEntity instance of the referred resource
     * @param array      $data         An array of the parameters from the request
     * @param bool       $clearMissing Set the missing data to NULL?
     *
     * @return BaseEntity
     * @throws Exception
     */
    private function saveOrUpdate(BaseEntity $entity, array $data, $clearMissing = true)
    {
        $formClass = $this->getFormClass();
        $form = $this->formFactory->create($formClass, $entity);
        $form->submit($data, $clearMissing);

        if ($form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();
        } else {
            throw new ValidationException($form->all());

        }

        return $entity;
    }
}