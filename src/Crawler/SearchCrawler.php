<?php
// florin 9/9/14 7:49 PM

namespace Flo\Torrentz\Crawler;

use Doctrine\Common\Persistence\ObjectManager;
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
    public function getTorrents()
    {
        $torrents = $this->filter('div.results > dl:has(a)')->each(function (SymfonyCrawler $node) {
            $title = $node->filter('a')->text();
            $url = $node->filter('a')->extract('href');
            if (is_array($url)) {
                $url = $url[0];
            }
            $hash = substr($url, 1); //remove dash
            $rating = (int) $node->filter('dd > .v')->text();
            $date = new \DateTime($node->filter('dd > .a > span')->attr('title'));
            $size = $node->filter('dd > .s')->text();
            $peers = str_replace(',', '', $node->filter('dd > .u')->text());
            $leechers = str_replace(',', '', $node->filter('dd > .d')->text());
            $dtText = $node->filter('dt')->text();
            $tagsString = trim(substr($dtText, strpos($dtText, '»') + 2));

            $torrent = new Torrent();
            $torrent
                ->setHash($hash)
                ->setTitle($title)
                ->setRating($rating)
                ->setDate($date)
                ->setSize($size)
                ->setPeers((int)$peers)
                ->setLeechers((int)$leechers);

            $tags = $this->getTags($tagsString);
            foreach ($tags as $tag) {
                /** @var Tag $tag */
                $torrent->addTag($tag);
            }

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
    public function getNumberOfSearchResults()
    {
        $text = $node = $this->filter('div.results > h2')->text();
        if (!preg_match('/^(\d{1,3}([,.]?\d{1,3})*)/i', $text, $matches)) {
            throw new \InvalidArgumentException('Error while parsing the number of search results');
        }
        $text = str_replace(',', '', $matches[0]);
        $number = (int)$text;
        return $number;
    }

    /**
     * Splits the tags string
     *
     * @param $string
     * @throws \InvalidArgumentException
     * @return array|boolean
     */
    protected function splitTagsString($string)
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException('Invalid tags argument');
        }
        $string = trim($string);
        $split = preg_split("/[\s,]+/", $string, -1, PREG_SPLIT_NO_EMPTY);
        return $split;
    }

    /**
     * @param $tagNames array array of tag names
     * @param TagRepository $repo
     * @return array of tags
     */
    protected function getTags($tagsString, TagRepository $repo = null)
    {
        $tagNames = $this->splitTagsString($tagsString);
        if ($repo) {
            $tagNames = $repo->getTagObjects($tagNames);
            return $tagNames;
        }
        $result = [];
        foreach ($tagNames as $name) {
            $tag = new Tag($name);
            $result[] = $tag;
        }
        return $result;
    }
}