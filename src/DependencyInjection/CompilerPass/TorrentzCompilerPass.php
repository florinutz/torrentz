<?php
// florin, 10/7/14, 12:11 AM
namespace Flo\Torrentz\DependencyInjection\CompilerPass;

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Flo\Nimic\Console\Application;
use Flo\Torrentz\Command\SearchCommand;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TorrentzCompilerPass implements CompilerPassInterface
{
    /** @var string */
    protected $appId;

    /**
     * @param string $appId application service id
     */
    function __construct($appId = 'app')
    {
        $this->appId = $appId;
    }

    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition($this->appId)) {
            $def = $container->getDefinition($this->appId);
            $def->setConfigurator(function(Application $app) use ($container) {
                $app->add(new SearchCommand());
            });
        }
    }
}