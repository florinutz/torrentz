<?php
// florin, 10/9/14, 7:54 PM
namespace Flo\Torrentz\Nimic;

use Flo\Nimic\Console\Application;
use Flo\Torrentz\DependencyInjection\CompilerPass\DoctrineCompilerPass;
use Flo\Nimic\Kernel\NimiKernel;
use Flo\Torrentz\DependencyInjection\CompilerPass\TorrentzCompilerPass;
use Flo\Torrentz\DependencyInjection\Extension\TorrentzExtension;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

abstract class Kernel extends NimiKernel
{
    protected $name = 'Torrentz';

    protected $version = '0.1';

    /** @var EntityManager */
    protected $em;

    function __construct($debug=false, $cacheDir=null)
    {
        parent::__construct($debug, $cacheDir);
    }

    protected function getExtensions()
    {
        // keep the existing ones so that Nimic doesn't break
        $extensions = parent::getExtensions();
        $extensions[] = new TorrentzExtension();
        return $extensions;
    }

    protected function getCompilerPasses()
    {
        $cPasses = parent::getCompilerPasses();
        $cPasses[] = new DoctrineCompilerPass('app');
        $cPasses[] = new TorrentzCompilerPass('app');
        return $cPasses;
    }

    /**
     * @param array $connectionParams
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    protected function getEntityManager(array $connectionParams)
    {
        if ($this->em) {
            return $this->em;
        }
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__."/../../src/Entity"], $this->isDebug());
        return $this->em = EntityManager::create($connectionParams, $config);
    }

    public function getContainer()
    {
        if ($this->container) {
            return $this->container;
        }

        $container = parent::getContainer();
        $emParams = $container->getParameter('db.config');

        $entityManager = $this->getEntityManager($emParams);
        $container->set('em', $entityManager);

        return $container;
    }

}