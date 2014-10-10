<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 10/9/14
 * Time: 5:31 PM
 */

namespace Blog\BaseCRUDBundle\Transformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use League\Fractal;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Blog\BaseCRUDBundle\Entity\BaseEntity;

class BlogViewTransformer
{
    private $view_handler;
    private $event;
    private $request;
    private $em;
    private $manager;
    private $controllerResult;
    private $transformer_namespace;
    private $optional = [];

    const RESOURCE_ITEM = 'Item';
    const RESOURCE_COLLECTION = 'Collection';

    /**
     * Constructor
     *
     * @param ViewHandler $view_handler
     * @param GetResponseForControllerResultEvent $event
     * @param EntityManager $em
     * @param null $transformer_namespace
     * @param null $optional
     */
    public function __construct(
        ViewHandler $view_handler,
        GetResponseForControllerResultEvent $event,
        EntityManager $em,
        $transformer_namespace = null,
        $optional = null
    ) {
        $this->view_handler = $view_handler;
        $this->event = $event;
        $this->em = $em;
        $this->request = $this->event->getRequest();
        $this->controllerResult = $this->event->getControllerResult();
        $this->transformer_namespace = $transformer_namespace;
        $this->optional = $optional;
    }

    /**
     * Run the transformation
     *
     */
    public function transform()
    {
        $this->manager = new Fractal\Manager();

        // set default recursion limit
        $this->manager->setRecursionLimit(3);

        // parse includes if presented
        if ($this->request->query->get('include')) {
            $this->manager->parseIncludes($this->request->query->get('include'));
        }

        // Only transform entities to allow passing in raw Views/Responses that won't get transformed
        if (gettype($this->controllerResult) == 'object') {
            if (strpos(get_class($this->controllerResult), $this->optional['entity_namespace']) === false) {
                return;
            }
        } elseif ($this->controllerResult == null) {
            // HTTP 204
            return;
        }

        // determine which resource type to use from this lower level controller result
        $resourceType =
            (gettype($this->controllerResult)) == 'object' ? self::RESOURCE_ITEM : self::RESOURCE_COLLECTION;

        // prepare the serializer
        $this->setSerializer();

        // depending on the action, there is a different underlying data type to work with
        if (!is_array($this->event->getControllerResult()) &&
            get_class($this->event->getControllerResult()) == 'FOS\RestBundle\View\View') {
            // FOS\RestBundle\View\View instance
            $controllerData = $this->controllerResult->getData();
        } else {
            // Entity instance(s)
            $controllerData = $this->controllerResult;
        }

        // serialize and prepare the data
        $data = $this->createData($this->createResource($controllerData, $resourceType));
        // prepare the data for injection
        $view = new View($data);

        $response = $this->view_handler->handle($view, $this->request);

        // overwrite the response
        $this->event->setResponse($response);
    }

    /**
     * Get the transformer name from the class of the controller
     * The value of $this->controllerResult can be either an object or an array
     * If no id is specified in the request, $this->controllerResult will be
     * an array of objects of tye type of the controller.
     * If an id is specified, $this->controllerResult will be a single object of
     * the type of the controller.
     *
     * @return string
     */
    public function getTransformerName()
    {
        // Check if the the controllerResult value is an array
        // This allows us to bypass an exception thrown if get_class
        // is not given an object. We are just trying to get the class
        // name so we don't care about the properties/methods of the object.
        if (is_array($this->controllerResult)) {
            $singleControllerResult = $this->controllerResult[0];
        } else {
            $singleControllerResult = $this->controllerResult;
        }
        // Retrieve the class name of the object
        $transformerName = get_class($singleControllerResult);
        // Separate the class path by '\' into array elements
        $transformerName = explode('\\', $transformerName);
        // Pop the last element of the array (transformer name)
        // into the string $transformerName.
        $transformerName = array_pop($transformerName);

        return $transformerName;
    }

    /**
     * Get current action name
     */
    public function getActionName()
    {
        $pattern = "/::([a-zA-Z]*)Action/";
        $matches = [];
        $matchActionResult = preg_match($pattern, $this->request->get('_controller'), $matches);
        if ($matchActionResult === 0 || $matchActionResult === false) {
            throw new NotFoundResourceException('Error finding the requested action.');
        }

        return $matches[1];
    }

    /**
     * Sets the manager's serializer
     */
    private function setSerializer()
    {

        if ($this->request->headers->get('X-Ember-Data') == true) {
            $this->manager->setSerializer(new Fractal\Serializer\JsonApiSerializer());
        } elseif ($this->request->getRequestFormat() == 'json') {
            $this->manager->setSerializer(new Fractal\Serializer\ArraySerializer());
        } else {
            $this->manager->setSerializer(new Fractal\Serializer\ArraySerializer());
        }
    }

    /**
     * Create a resource
     *
     * @param $data
     * @param $resourceType
     *
     * @return Fractal\Resource\Collection|Fractal\Resource
     */
    private function createResource($data, $resourceType)
    {
        $transformer = $this->transformer_namespace . '\\' . $this->getTransformerName() . 'Transformer';

        switch($resourceType) {
            case self::RESOURCE_ITEM:
                return $resource = new Fractal\Resource\Item(
                    $data,
                    new $transformer($this->em),
                    $transformer::$resourceKey['singular']
                );
                break;
            case self::RESOURCE_COLLECTION:
                return $resource = new Fractal\Resource\Collection(
                    $data,
                    new $transformer($this->em),
                    $transformer::$resourceKey['plural']
                );
                break;
        }
    }

    /**
     * Retrieves an array of data from the resource
     *
     * @param $resource
     * @return mixed
     */
    private function createData($resource)
    {
        return $this->manager->createData($resource)->toArray();
    }
}
