<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 9/30/14
 * Time: 11:04 AM
 */

namespace Blog\BaseCRUDBundle\Tests\Controller;

use Blog\BaseCRUDBundle\Controller\BaseCRUDController;
use Blog\BaseCRUDBundle\Tests\Entity\FakeEntity;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class BaseCRUDControllerTest
 *
 * @package Blog\BaseCRUDBundle\Tests\Controller
 */
class BaseCRUDControllerTest extends KernelTestCase
{
    /**
     * @var BaseCRUDController
     */
    private static $controller;

    /**
     * Run before test class execution.
     * It creates the FakeEntity table and add a few Fixtures for tests
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        // we need to boot a kernel for tests
        static::bootKernel();

        // create test database
        /**
         * @var $em \Doctrine\ORM\EntityManager
         */
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        // Drop and recreate tables for all entities
        $schemaTool->updateSchema($metadata);

        // adding some fake data to the database
        $fakeEntity = new FakeEntity();
        $fakeEntity->setFirstName('Test');
        $fakeEntity->setLastName('Entity');
        $em->persist($fakeEntity);
        $em->flush();

        $fakeEntity = new FakeEntity();
        $fakeEntity->setFirstName('Delete');
        $fakeEntity->setLastName('This');
        $em->persist($fakeEntity);
        $em->flush();
    }

    /**
     * Runs before each test execution.
     * For each test we need to boot a new kernel and retrieve a new instance of BaseEntityController.
     *
     * @return void
     */
    public function setUp()
    {
        // start a new fresh kernel
        static::bootKernel();

        // On every test, create a new instance of the target class
        static::$controller = new BaseCRUDController();
        static::$controller->setContainer(static::$kernel->getContainer());

    }

    /**
     * Runs after each test execution.
     * Used to shutdown the kernel.
     *
     * @return void
     */
    public function tearDown()
    {
        // kill the kernel
        static::$kernel->shutdown();
    }

    /**
     * Trying to access the wrong service
     *
     * @expectedException   \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function testGetActionWithServiceTypedWrong()
    {
        // try to retrieve an entity
        static::$controller->getAction(1, 'blog_base_crud.fake_entity_service_wrong');

        // an exception should be thrown
    }

    /**
     * Trying to access the right service with an id for a non existing item
     *
     * @expectedException   \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetActionWithNonExistingId()
    {
        // try to retrieve an entity
        static::$controller->getAction(100, 'blog_base_crud.fake_entity_service');

        // an exception should be thrown
    }


    /**
     * Try to access the right service with a valid id
     */
    public function testGetActionWithValidIdAndService()
    {
        // try to retrieve an entity
        /* @var $entity FakeEntity */
        $entity = static::$controller->getAction(1, 'blog_base_crud.fake_entity_service');

        // make sure if this is the expected data
        $this->assertEquals($entity->getId(), 1);
        $this->assertEquals($entity->getFirstName(), 'Test');
        $this->assertEquals($entity->getLastName(), 'Entity');

        // this is the DATA!!!!!
    }


    /**
     * Try to use the addAction with good data but at an inexisting service
     *
     * @expectedException   \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function testAddActionWithServiceTypedWrong()
    {
        // create an empty request
        $request = Request::create(
            'fakeURL', // URI
            'POST', // HTTP Method
            ['firstName' => 'Add', 'lastName' => 'test'] // Parameters
        );

        // try to access the add action at a wrong service
        static::$controller->addAction($request, 'apple_base_entities.fake_entity_service_wrong');

        // A validation exception should be raised
    }


    /**
     * Try to use the addAction with empty data at the right service
     *
     * @expectedException   \Symfony\Component\Form\Exception\InvalidArgumentException
     */
    public function testAddActionWithEmptyData()
    {
        // create an empty request
        $request = Request::create(
            'fakeURL', // URI
            'POST', // HTTP Method
            [] // Parameters
        );

        // try to access the add action at a wrong service
        static::$controller->addAction($request, 'blog_base_crud.fake_entity_service');

        // A validation exception should be raised
    }


    /**
     * Try to use the addAction with missing data at the right service
     */
    public function testAddActionWithMissingData()
    {
        // create an empty request
        $request = Request::create(
            'fakeURL', // URI
            'POST', // HTTP Method
            ['firstName' => 'Add'] // Parameters
        );

        // try to access the add action at a wrong service
        $response = static::$controller->addAction($request, 'blog_base_crud.fake_entity_service');

        // Validate if the response is a View
        $this->assertInstanceOf('FOS\RestBundle\View\View', $response);

        // get the data
        $data = $response->getData();

        // verify if there is an error
        $this->assertArrayHasKey('errors', $data);

    }


    /**
     * Try to use the add action with correct data at the right service
     */
    public function testAddAction()
    {
        // create an empty request
        $request = Request::create(
            'fakeURL', // URI
            'POST', // HTTP Method
            ['firstName' => 'Add', 'lastName' => 'test'] // Parameters
        );

        // try to access the add action at a wrong service
        $response = static::$controller->addAction($request, 'blog_base_crud.fake_entity_service');

        // Assert if the response is the expected view type
        $this->assertInstanceOf('FOS\RestBundle\View\View', $response);

        // Get the returned data
        $data = $response->getData();

        // Assert if there is an ID
        $this->assertArrayHasKey('id', $data);
    }

}
