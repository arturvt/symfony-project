<?php

namespace Project\Test\testBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ProjectTesttestExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->mapParameters($container, $config);
    }

    private function mapParameters(ContainerBuilder $container, $config)
    {
        $parameters = [
            'transformer_namespace', 'optional'
        ];

        foreach ($parameters as $param) {
            $container->setParameter(sprintf('blog_fractal_transform.fractal_transform.%s', $param), $config[$param]);
        }
    }

}


