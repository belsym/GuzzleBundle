<?php

namespace Belsym\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;

class BelsymGuzzleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.xml');

        $processor = new Processor();
        $configuration = new Configuration($container->getParameter('kernel.debug'));
        $config = $processor->processConfiguration($configuration, $configs);

        if(isset($config['configuration']['configuration_file']) && (!isset($config['configuration']['services']) || empty($config['configuration']['services'])))
        {
            $container->setParameter('guzzle.service.configuration',
                $config['configuration']['configuration_file']);
        }
        else
        {
            $container->setParameter('guzzle.service.configuration',
                $config['configuration']);
        }

        $container->setParameter('guzzle.service.global_configuration', array());

        if ($config['logging']) {
            $container->findDefinition('guzzle.data_collector')
                ->addTag('data_collector', array('template' => 'BelsymGuzzleBundle:Collector:guzzle', 'id' => 'guzzle'));
        }
    }
}
