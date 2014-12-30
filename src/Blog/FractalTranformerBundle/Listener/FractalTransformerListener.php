<?php

namespace Blog\FractalTranformerBundle\Listener;
use FOS\RestBundle\View\ViewHandler;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class FractalTransformerListener
{
    /**
     * Constructor
     *
     * @param ViewHandler $fos_rest_view_handler
     * @param EntityManager $em
     * @param null $transformer_namespace
     * @param null $optional
     */
    public function __construct(
        ViewHandler $fos_rest_view_handler = null,
        EntityManager $em = null,
        $transformer_namespace = null,
        $optional = null
    ) {
        $this->view_handler = $fos_rest_view_handler;
        $this->em = $em;
        $this->transformer_namespace = $transformer_namespace;
        $this->optional = $optional;
    }

}
