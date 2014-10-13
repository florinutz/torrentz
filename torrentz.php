<?php
require "vendor/autoload.php";
use Flo\Torrentz\Nimic\Kernel;

$kernel = new Kernel();
$kernel->getApplication()->run();