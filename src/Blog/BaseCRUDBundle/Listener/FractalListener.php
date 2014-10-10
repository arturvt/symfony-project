<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 10/9/14
 * Time: 5:29 PM
 */

namespace Blog\BaseCRUDBundle\Listener;

use Blog\BaseCRUDBundle\Transformer\BlogViewTransformer;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use FOS\RestBundle\View\ViewHandler;
use Doctrine\ORM\EntityManager;
use League\Fractal;

class FractalListener
{
    protected $view_handler;
    protected $em;
    private $transformer_namespace;
    private $optional = [];

    /**
     * Constructor
     *
     * @param ViewHandler $fos_rest_view_handler
     * @param EntityManager $em
     * @param null $transformer_namespace
     * @param null $optional
     */
    public function __construct(
        ViewHandler $fos_rest_view_handler,
        EntityManager $em,
        $transformer_namespace = null,
        $optional = null
    ) {
        $this->view_handler = $fos_rest_view_handler;
        $this->em = $em;
        $this->transformer_namespace = $transformer_namespace;
        $this->optional = $optional;
    }

    /**
     * Transform the view
     *
     * @param GetResponseForControllerResultEvent $event
     * @return BlogViewTransformer
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $blogViewTransformer = new BlogViewTransformer(
            $this->view_handler,
            $event,
            $this->em,
            $this->transformer_namespace,
            $this->optional
        );
        return $blogViewTransformer->transform();
    }
}
