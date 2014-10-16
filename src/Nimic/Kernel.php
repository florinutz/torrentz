<?php
// florin, 10/9/14, 7:54 PM
namespace Flo\Torrentz\Nimic;

use Flo\Nimic\Kernel\NimiKernel;
use Flo\Torrentz\DependencyInjection\Extension\TorrentzExtension;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

    /**
     * @param array $connectionParams
     * @return \Doctrine\ORM\EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    protected function getEntityManager(array $connectionParams)
    {
        if ($this->em) {
            return $this->em;
        }
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__."/src/Entities"], $this->isDebug());
        return $this->em = EntityManager::create($connectionParams, $config);
    }

}