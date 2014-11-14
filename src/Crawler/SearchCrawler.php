<?php
// florin 9/9/14 7:49 PM

namespace Flo\Torrentz\Crawler;

use Flo\Torrentz\Crawler\Util\TagsHandler;
use Flo\Torrentz\Entity\Repository\TagRepository;
use Flo\Torrentz\Entity\Tag;
use Flo\Torrentz\Entity\Torrent;
use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

/**
 * This class parses a search page and retrieves all useful data (the torrent list and the number of search results)
 *
 * Class SearchPageCrawler
 * @package Dubios\Torrentz\CrawlerBundle\Crawler
 */
class SearchCrawler extends SymfonyCrawler
{
    protected function getTorrents(TagsHandler $tagsHandler)
    {
        $torrents = $this->filter('div.results > dl')->each(function (Crawler $node) use($tagsHandler) {
            try {
                $title = $node->filter('a')->text();
                $url = $node->filter('a')->extract('href');
                if (is_array($url)) {
                    $url = $url[0];
                }
                $hash = substr($url, 1); //remove dash
                $rating = $node->filter('dd > .v')->text();
                $date = new \DateTime($node->filter('dd > .a > span')->attr('title'));
                $size = $node->filter('dd > .s')->text();
                $peers = str_replace(',', '', $node->filter('dd > .u')->text());
                $leechers = str_replace(',', '', $node->filter('dd > .d')->text());
                $dtText = $node->filter('dt')->text();
                $tagsString = trim(substr($dtText, strpos($dtText, '»') + 2));
            } catch (\InvalidArgumentException $e) {
                return false;
            }
            $torrent = new Torrent();
            $torrent
                ->setHash($hash)
                ->setTitle($title)
                ->setRating($rating)
                ->setDate($date)
                ->setSize($size)
                ->setPeers((int)$peers)
                ->setLeechers((int)$leechers);
            // no flush for new tags:
            $tagNames = $tagsHandler->splitTags($tagsString);
            $tagsHandler->addTagsToTorrent($tagNames, $torrent, true);
            return $torrent;
        });
        return $torrents;
    }

    /**
     * Parses the number of results
     *
     * @throws \InvalidArgumentException
     * @return mixed
     */
    protected function getNumberOfSearchResults()
    {
        $text = $node = $this->filter('div.results > h2')->text();
        if (!preg_match('/^(\d{1,3}([,.]?\d{1,3})*)/i', $text, $matches)) {
            throw new \InvalidArgumentException('Error while parsing the number of search results');
        }
        $text = str_replace(',', '', $matches[0]);
        $number = (int)$text;
        return $number;
    }
}