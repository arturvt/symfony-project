<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 9/30/14
 * Time: 11:31 AM
 */

namespace Blog\BaseCRUDBundle\Tests\Service;

use Mockery as Mocker;
use Blog\BaseCRUDBundle\service\BaseCRUDService;
use Blog\BaseCRUDBundle\Tests\Entity\FakeEntity;
use Blog\DoctrineExceptionBundle\Service\DoctrineExceptionInterpreter;
use Doctrine\DBAL\DBALException;
use Blog\BaseCRUDBundle\Util\ValidationException;

class BaseCRUDServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Addresses the entity manager to be mocked
     */
    const ENTITY_MANAGER = 'Doctrine\ORM\EntityManager';

    /**
     * Addresses the entity repository to be mocked
     */
    const ENTITY_REPOSITORY = '\Doctrine\ORM\EntityRepository';

    /**
     * Addresses the form factory to be mocked
     */
    const FORM_FACTORY = 'Symfony\Component\Form\FormFactory';

    /**
     * Addresses the form to be mocked
     */
    const FORM = 'Symfony\Component\Form\Form';

    /**
     * Addresses the Doctrine to be mocked
     */
<<<<<<< HEAD
    const DOCTRINE_EXCEPTION_INTERPRETER = 'Blog\DoctrineExceptionBundle\Service\DoctrineExceptionInterpreter';
=======
    const DOCTRINE_EXCEPTION_INTERPRETER = 'Apple\DoctrineExceptionBundle\Service\DoctrineExceptionInterpreter';
>>>>>>> refs/heads/master(github)

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManagerMock;
    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactoryMock;

    /**
     * @var DoctrineExceptionInterpreter
     */
    private $doctrineExceptionInterpreter;

    /**
     * Creates mock objects before class it' executed
     */
    protected function setUp()
    {
        $this->createServiceMocks();
    }

    /**
     * Function to be used upon demand for creating mock for injected services.
     */
    protected function createServiceMocks()
    {
        $this->entityManagerMock = Mocker::mock(self::ENTITY_MANAGER);
        $this->formFactoryMock = Mocker::mock(self::FORM_FACTORY);
        $this->doctrineExceptionInterpreter = Mocker::mock(self::DOCTRINE_EXCEPTION_INTERPRETER);
    }

    /**
     * Creates a mocked ProjectEntityService
     *
     * @return BaseCRUDService
     */
    private function getBaseEntityService()
    {
        // As we testing the BaseEntityService, it can be chosen or FakerEntity.
        return new BaseCRUDService(
            $this->entityManagerMock,
            $this->formFactoryMock,
            'Blog\BaseCRUDBundle\Tests\Form\FakeEntityType',
            'Blog\BaseCRUDBundle\Tests\Entity\FakeEntity',
            $this->doctrineExceptionInterpreter
        );
    }

    /**
     * Mocking the EntityManager->getRepository()->find($id) function and returning an object passed as a parameter
     *
     * @param $returnObject
     * @return Mocker\MockInterface
     */
    private function setEntityManagerFindByIdMock($returnObject)
    {
        $fakeEntityRepositoryMock = Mocker::mock(self::ENTITY_REPOSITORY);
        $fakeEntityRepositoryMock->shouldReceive('find')->andReturn($returnObject);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->entityManagerMock->shouldReceive('getRepository')->times(1)->andReturn($fakeEntityRepositoryMock);
    }

    /**
     * Trying to get a fakeEntity with null id
     *
     * @expectedException        \Symfony\Component\Form\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid id.
     */
    public function testGetByIdWithNullId()
    {
        // Given a basic entity service instance
        $service = $this->getBaseEntityService();

        // When trying to find a fake entity byId with a null pointer.
        $service->getById(null);

        // Then a InvalidArgumentException should be thrown
    }

    /**
     * Trying to get a BaseEntity with a non-numeric id
     *
     * @expectedException        \Symfony\Component\Form\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid id.
     */
    public function testGetByIdWithNonNumericId()
    {
        // Given a basic entity service instance
        $service = $this->getBaseEntityService();

        // When trying to find a fake entity byId with a non numeric Id.
        $service->getById('test');

        // Then a InvalidArgumentException should be thrown
    }

    /**
     * Trying to get a BaseEntity with a non-existent id
     *
     * @expectedException        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Unable to find resource.
     */
    public function testGetByIdWithNonExistent()
    {
        // Given a basic entity service instance
        $this->setEntityManagerFindByIdMock(null);

        $service = $this->getBaseEntityService();

        // When trying to find a fake entity byId with a non existent fake entity.
        $service->getById(99999);

        // Then a NotFoundHttpException should be thrown
    }

    /**
     * Getting a BaseEntity with a valid ID
     * Should succeed.
     */
    public function testGetByIdSuccess()
    {
        // Given a basic entity service instance
        $expected =  new FakeEntity();
        $expected->setFirstName('Hardware');

        $this->setEntityManagerFindByIdMock($expected);

        $service = $this->getBaseEntityService();

        // When trying to find a fake entity by Id with a valid value.
        $fakeEntity = $service->getById(1);

        // Then we should expect a valid fake entity.
        $this->assertEquals($expected, $fakeEntity);
    }

    /**
     * Trying to create a Object with an empty ObjectData array
     *
     * @expectedException   \Symfony\Component\Form\Exception\InvalidArgumentException
     */
    public function testAddWithEmptyObjectData()
    {
        // Given a basic entity service instance
        $service = $this->getBaseEntityService();

        // When I try to add a null FakeEntity
        $service->add([]);

        // Then it should thrown an InvalidArgumentException
    }


    /**
     * Updating a BaseEntity object.
     * In this case using fakeEntity as example.
     * Should succeed.
     */
    public function testUpdateSuccess()
    {
        // Given a basic entity service instance
        $service = $this->getBaseEntityService();

        // When I try to update a fakeEntity
        $fakeEntity =  new FakeEntity();
        $fakeEntity->setFirstName('FirstName');

        $this->setEntityManagerFindByIdMock($fakeEntity);

        $formMock = Mocker::mock(self::FORM);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->formFactoryMock->shouldReceive('create')->withAnyArgs()->times(1)->andReturn($formMock);
        $formMock->shouldReceive('submit')->times(1);
        $formMock->shouldReceive('isValid')->times(1)->andReturn(true);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->entityManagerMock->shouldReceive('persist')->times(1);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->entityManagerMock->shouldReceive('flush')->times(1);

        $service->update($id = 1, $fakeEntityData = ['firstName' => 'Fake Name'], $editOrUpdate = true);

        // Then it should thrown an InvalidArgumentException
    }

    /**
     * Update a Object with an invalid data
     * In this case using FakeEntity as example.
     *
     * @expectedException   ValidationException
     */
    public function testUpdateWithInvalidFakeEntity()
    {
        // Given a basic entity service instance
        $service = $this->getBaseEntityService();

        // When I try to update an invalid fakeEntity
        $fakeEntity =  new FakeEntity();

        $this->setEntityManagerFindByIdMock($fakeEntity);

        $formMock = Mocker::mock(self::FORM);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->formFactoryMock->shouldReceive('create')->withAnyArgs()->times(1)->andReturn($formMock);
        $formMock->shouldReceive('submit')->times(1);
        $formMock->shouldReceive('isValid')->times(1)->andReturn(false);
        $formMock->shouldReceive('all')->times(1)->andReturn([]);

        $service->update($id = 1, $fakeEntityData = ['firstName' => 'Fake Data'], $editOrUpdate = false);

        // Then it should thrown an InvalidArgumentException
    }

    /**
     * Trying to delete a BaseEntity with null id
     *
     * @expectedException   \Symfony\Component\Form\Exception\InvalidArgumentException
     */
    public function testDeleteByNullId()
    {
        // Given a basic entity service instance
        $service = $this->getBaseEntityService();

        // When I try to delete a null FakeEntity
        $service->delete(null);

        // Then it should throw an InvalidArgumentException
    }

    /**
     * Trying to delete a BaseEntity with a non-existent id
     *
     * @expectedException   \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testDeleteWithNonExistentId()
    {
        // Given a basic entity service instance
        $this->setEntityManagerFindByIdMock(null);

        $service = $this->getBaseEntityService();

        // When I try to delete a non existent fakeEntity
        $service->delete(99999);

        // Then it should throw an NotFoundHttpException
    }

    /**
     * Deleting a BaseEntity
     * Should succeed.
     */
    public function testDeleteSuccess()
    {
        // Given a basic entity  service instance
        $deleted = new FakeEntity();

        $this->setEntityManagerFindByIdMock($deleted);

        $service = $this->getBaseEntityService();

        // When trying to delete a FakeEntity by Id with a valid value.
        /** @noinspection PhpUndefinedMethodInspection */
        $this->entityManagerMock->shouldReceive('remove')->times(1);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->entityManagerMock->shouldReceive('flush')->times(1);
        $service->delete(1);

        // Then the fake entity should be deleted.
    }

    /**
     * Should throw a validation exception for an 'unexpected error'
     *
     * @expectedException   ValidationException
     *
     */
    public function testDeleteUnexpectedError()
    {
        // Given a basic entity service instance
        $deleted = new FakeEntity();

        $this->createServiceMocks();

        $service = $this->getBaseEntityService();

        $this->setEntityManagerFindByIdMock($deleted);

        // When trying to delete a FakeEntity by Id with a valid value.
        /** @noinspection PhpUndefinedMethodInspection */
        $this->entityManagerMock->shouldReceive('remove')->
        andThrow(new DBALException(null, '00', new \PDOException(null, '00')));

        /** @noinspection PhpUndefinedMethodInspection */
        $this->doctrineExceptionInterpreter->shouldReceive('isIntegrityConstraintViolation')->times(1);

        $service->delete(1);
        // ValidationException should be thrown
    }

    /**
     * Trying to create a FakeEntity with an invalid entity
     *
     * @expectedException   \DomainException
     */
    public function testInvalidForm()
    {
        // Given a basic entity service instance
        $service = new BaseCRUDService(
            $this->entityManagerMock,
            $this->formFactoryMock,
            'Blog\BaseCRUDBundle\Tests\Form\InvalidFakeEntityType',
            'Blog\BaseCRUDBundle\Tests\Entity\FakeEntity',
            $this->doctrineExceptionInterpreter
        );

        $form = Mocker::mock(self::FORM);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->formFactoryMock->shouldReceive('create')->withAnyArgs()->andReturn($form);
        $form->shouldReceive('submit')->times(1);
        $form->shouldReceive('isValid')->times(1)->andReturn(true);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->entityManagerMock->shouldReceive('persist')->withAnyArgs();
        /** @noinspection PhpUndefinedMethodInspection */
        $this->entityManagerMock->shouldReceive('flush');

        $service->add(array('firstName' => 'fake name', 'lastName' => ''));
    }
}