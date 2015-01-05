<?php
// florin 11/12/14 3:46 PM
namespace Flo\Torrentz\Command;

use Flo\Torrentz\Entity\Torrent;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\FutureResponse;
use GuzzleHttp\Message\Response;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Flo\Torrentz\Crawler\SearchCrawler;

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
            ->addOption('ua', null, InputOption::VALUE_OPTIONAL, 'User agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $searched = $input->getArgument('query');
        $client = new GuzzleClient(['base_url' => $input->getOption('domain'), 'defaults' => ['headers' => ['User-Agent' => $input->getOption('ua')]]]);
        $request = $client->createRequest('GET', 'search', ['future' => true, 'query' => ['q' => htmlentities($input->getArgument('query'))]]);
        /** @var FutureResponse $response */
        $response = $client->send($request)->then(
            function($response) use($output) {
                /** @var Response $response */
                $bodyStr = (string) $response->getBody();
                $crawler = new SearchCrawler($bodyStr);
                $searchResults = $crawler->getNumberOfSearchResults();
                $torrents = $crawler->getTorrents();
                foreach ($torrents as $torrent) {
                    /** @var Torrent $torrent */
                    if (!$torrent->getId()) {

                    }
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