<?php
// florin 11/12/14 3:46 PM
namespace Flo\Torrentz\Command;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\FutureResponse;
use GuzzleHttp\Message\Response;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\DomCrawler\Crawler;

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
            ->addOption('domain', null, InputOption::VALUE_OPTIONAL, 'Domain part of url', 'https://torrentz.eu/')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $searched = $input->getArgument('query');
        $client = new GuzzleClient(['base_url' => 'http://dubios.ro:8000', 'defaults' => [
            'headers' => ['User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36']
        ]]);
        $request = $client->createRequest('GET', '/links/100/89', ['future' => true]);
        /** @var FutureResponse $response */
        $response = $client->send( $request );
        $response->then(
            function($response) use($output) {
                /** @var Response $response */
                $bodyStr = (string) $response->getBody();
                $crawler = new Crawler($bodyStr);;
                foreach ($crawler->filter('body > a') as $domElement) {
                    /** @var \DOMElement $domElement */
                    $output->writeln($domElement->nodeValue . " ({$domElement->nodeName})");
                }
                return $response;
            },
            function($error) {
                /** @var RequestException $error */
                echo 'Exception: ' . $error->getMessage();
                throw $error;
            }
        );
        return;
    }

    protected function handleSuccess(Response $response) {

    }

}