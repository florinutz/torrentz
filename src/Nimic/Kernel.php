<?php
// florin, 10/9/14, 7:54 PM
namespace Flo\Torrentz\Nimic;

use Flo\Nimic\Kernel\NimiKernel;
use Flo\Torrentz\DependencyInjection\Extension\TorrentzExtension;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

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
        $extensions = parent::getExtensions();
        $extensions[] = new TorrentzExtension();
        return $extensions;
    }

    /**
     * @param array $connectionParams
     * @throws \Doctrine\ORM\ORMException
     */
    protected function initEntityManager(array $connectionParams)
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__."/src"], $this->isDebug());
        $connectionParams = [
            'dbname' => 'torrentz',
            'user' => 'root',
            'password' => '****',
            'host' => 'localhost',
            'driver' => 'mysqli',
        ];
        $entityManager = EntityManager::create($connectionParams, $config);
    }

}