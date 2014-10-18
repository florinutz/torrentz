<?php
require "vendor/autoload.php";
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

class MyKernel extends Flo\Torrentz\Nimic\Kernel
{
    protected function loadConfigResourcesIntoContainer(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('config.yml');
    }
}

$kernel = new \MyKernel();

$app = $kernel->getApplication();

/** @var \Doctrine\ORM\EntityManager $em */
$em = $kernel->getContainer()->get('em');

$app->run();
