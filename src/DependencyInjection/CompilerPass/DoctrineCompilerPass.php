<?php
// florin, 10/7/14, 12:11 AM
namespace Flo\Torrentz\DependencyInjection\CompilerPass;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Flo\Nimic\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DoctrineCompilerPass implements CompilerPassInterface
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
                $em = $container->get('em');
                $helperSet = ConsoleRunner::createHelperSet($em);
                $app->setHelperSet($helperSet);
                ConsoleRunner::addCommands($app);
            });
        }
    }
}