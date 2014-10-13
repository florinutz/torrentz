<?php
// florin, 10/9/14, 7:54 PM
namespace Flo\Torrentz\Nimic;

use Flo\Nimic\Kernel\NimiKernel;
use Flo\Torrentz\DependencyInjection\Extension\TorrentzExtension;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Kernel extends NimiKernel
{
    protected $name = 'Torrentz';
    protected $version = '0.1';

    function __construct($debug=false, $cacheDir=null)
    {
        parent::__construct($debug, $cacheDir);
        $this->initDoctrine();
    }

    protected function getExtensions()
    {
        $extensions = parent::getExtensions();
        $extensions[] = new TorrentzExtension();
        return $extensions;
    }

    public function initDoctrine()
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__."/src"], $this->isDebug());
        $connectionParams = [
            'dbname' => 'torrentz',
            'user' => 'root',
            'password' => '****',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ];
        $entityManager = EntityManager::create($connectionParams, $config);
    }
}