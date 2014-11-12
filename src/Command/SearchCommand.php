<?php
// florin 11/12/14 3:46 PM
namespace Flo\Torrentz\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SearchCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('search')
            ->setDescription('Search torrents')
            ->addArgument(
                'query',
                InputArgument::REQUIRED,
                'What are we searching for?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('query');
        $output->writeln($name);
    }

}