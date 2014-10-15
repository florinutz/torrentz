<?php
require "vendor/autoload.php";

class MyKernel extends Flo\Torrentz\Nimic\Kernel
{
    function getConfigFile()
    {
        return $this->getRootDir() . '/config.yml';
    }

    public function getRootDir()
    {
        return __DIR__;
    }
}

$kernel = new \MyKernel();
$params = $kernel->getContainer()->getParameterBag()->all();
$kernel->getApplication()->run();