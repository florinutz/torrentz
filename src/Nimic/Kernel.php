<?php
// florin, 10/9/14, 7:54 PM
namespace Flo\Torrentz\Nimic;

use Flo\Nimic\Kernel\NimiKernel;
use Flo\Nimic\DependencyInjection\Extension\TorrentzExtension;

class Kernel extends NimiKernel
{
    protected function getExtensions()
    {
        $extensions = parent::getExtensions();
        $extensions[] = new TorrentzExtension();
        return $extensions;
    }
}