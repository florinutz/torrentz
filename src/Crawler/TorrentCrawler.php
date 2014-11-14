<?php
// florin 9/9/14 7:49 PM

namespace Dubios\Torrentz\CrawlerBundle\Client\Crawler;

use Flo\Torrentz\Crawler\Util\TagsHandler;
use Flo\Torrentz\Entity\Repository\TagRepository;
use Flo\Torrentz\Entity\Tag;
use Flo\Torrentz\Entity\Url;
use Flo\Torrentz\Entity\Torrent;
use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

/**
 * This class parses a torrent page and retrieves all useful data (links to get the magnet / torrent from)
 *
 * Class TorrentCrawler
 * @package Dubios\Torrentz\CrawlerBundle\Client\Crawler
 */
class TorrentCrawler extends SymfonyCrawler
{
    /**
     * @var Torrent
     */
    protected $torrent;

    /** @var array */
    private $urls;

    /**
     * Retrieves a torrent object from the page
     *
     * @param TagsHandler $tagsHandler
     * @return Torrent
     */
    public function getTorrent(TagsHandler $tagsHandler)
    {
        if (isset($this->torrent)) {
            return $this->torrent;
        }
        $hash = $this->getTorrentHash();
        $torrent = new Torrent();
        $torrent->setHash($hash);
        $this->fillTorrentData($torrent, $tagsHandler);
        return $this->torrent = $torrent;
    }


    public function getUrls()
    {
        if (isset($this->urls)) {
            return $this->urls;
        }
        $this->urls = $this->filter('div.download > dl')->each(function (Crawler $node) {
            try {
                $dt = $node->filter('dt>a');
                $dd = $node->filter('dd');
                if (strpos(strtolower($dd->text()), 'sponsor') !== false) {
                    return false;
                }
                else {
                    $additionTimeOnDomain = new \DateTime($dd->filter('span')->attr('title'));
                }
                $link = $dt->attr('href');
                $domain = $dt->filter('span.u')->text();
                $torrentNameOnDomain = $dt->filter('span.n')->text();

                $url = new Url();
                $url->setUrl($link);
                // todo make sure this $domain string is somewhere converted to Domain
                $url->setDomain($domain);
                // todo make sure each $url has a $torrent
                //$url->setTorrent($this->getTorrent());
                $url->setTitle($torrentNameOnDomain);
                $url->setDate($additionTimeOnDomain);
                return $url;
            } catch (\InvalidArgumentException $e) {
                return false;
            }
        });
        foreach ($this->urls as $i=>$url) {
            if (!$url) {
                unset($this->urls[$i]);
            }
        }
        return  $this->urls;
    }

    /**
     * TODO move this somewhere
     * @param array $urls
     */
    public function saveUrls($urls)
    {
        foreach ($urls as $url) {
            /**
             * @var Url $url
             * @var Url $existing
             */
            $manager = $this->container->get('doctrine')->getManager();
            if ($existing = $manager->getRepository('DubiosTorrentzCrawlerBundle:Url')->alreadyExists($url)) {
                $existing->setTitle($url->getTitle());
                $existing->setDate($url->getDate());
                $url = $existing;
            }
            else {
                $manager->persist($url);
            }
            $manager->flush($url);
        }
    }

    protected function fillTorrentData(Torrent $torrent, TagsHandler $tagsHandler)
    {
        $date = $this->getDate();
        $hash = $this->getTorrentHash();
        $goodRate = $this->getGoodRate();
        $fakeRate = $this->getFakeRate();
        $passwordRate = $this->getPasswordRate();
        $lowQualityRate = $this->getLowQualityRate();
        $virusRate = $this->getVirusRate();
        $title = $this->getTitle();
        $shortestTitle = $this->getShortestTitle();
        $size = $this->getSize();
        $peers = $this->getSeeders();
        $leechers = $this->getLeechers();

        $torrent->setDate($date);
        $torrent->setHash($hash);
        $torrent->setPeers($peers);
        $torrent->setLeechers($leechers);
        $torrent->setRating($goodRate['rate']);
        $torrent->setRatedBy($goodRate['by']);
        $torrent->setFakeRate($fakeRate);
        $torrent->setPasswordRate($passwordRate);
        $torrent->setLowQualityRate($lowQualityRate);
        $torrent->setVirusRate($virusRate);
        $torrent->setTitle($title);
        $torrent->setShortestTitle($shortestTitle);
        $torrent->setSize($size);

        //todo update tags for torrent
        //todo maybe even set tags for each url?
        $tags = $this->getTagNames($tagsHandler);
        foreach ($tags as $tag) {
            /** @var Tag $tag */
            $torrent->addTag($tag);
        }
    }

    public function getTagNames(TagsHandler $tagsHandler)
    {
        $multi = $this->filter('div.download dt > a')->each(function (Crawler $node) {
            $html = $node->html();
            $split = preg_split('/\<\/span\>/i', $html, null, PREG_SPLIT_DELIM_CAPTURE);
            $tagsText = trim(end($split));
            return $tagsText;
        });
        $tags = [];
        foreach ($multi as $tagText) {
            $ts = $tagsHandler->splitTags($tagText);
            foreach ($ts as $name) {
                if (!in_array($name, $tags)) {
                    $tags[] = $name;
                }
            }
        }
        return $tags;
    }

    public function getSeeders()
    {
        $text = $this->filter('div.trackers > dl span.u')->eq(0)->text();
        $text = str_replace(',', '', $text);
        return (int) $text;
    }

    public function getLeechers()
    {
        $text = $this->filter('div.trackers > dl span.d')->eq(0)->text();
        $text = str_replace(',', '', $text);
        return (int) $text;
    }

    /**
     * in bits
     * @return int
     */
    public function getSize()
    {
        $size = $this->filter('div.files > div')->attr('title');
        $size = str_replace([',', 'b'], '', $size);
        return (int) $size;
    }

    public function getTitle()
    {
        $title = $this->filter('div.download > h2 > span')->text();
        return $title;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        $timeStr = $this->filter('div.download > div > span')->attr('title');
        $date = new \DateTime($timeStr);
        return $date;
    }

    /**
     * @return array
     */
    public function getGoodRate()
    {
        $noteBox = $this->filter('div.votebox');
        $rate = $noteBox->filter('span.status')->text();
        $goodStr = $noteBox->filter('a')->text();
        preg_match("/\((\d*)\)$/i", $goodStr, $matches);
        return [ 'rate' => (int)$rate, 'by' => (int)$matches[1] ];
    }

    public function getFakeRate()
    {
        $smallRatesBox = $this->filter('span.replist > a');
        $text = $smallRatesBox->eq(0)->text();
        preg_match("/(\d+)/i", $text, $matches);
        return (int)$matches[0];
    }

    public function getPasswordRate()
    {
        $smallRatesBox = $this->filter('span.replist > a');
        $text = $smallRatesBox->eq(1)->text();
        preg_match("/(\d+)/i", $text, $matches);
        return (int)$matches[0];
    }

    public function getLowQualityRate()
    {
        $smallRatesBox = $this->filter('span.replist > a');
        $text = $smallRatesBox->eq(2)->text();
        preg_match("/(\d+)/i", $text, $matches);
        return (int)$matches[0];
    }

    public function getVirusRate()
    {
        $smallRatesBox = $this->filter('span.replist > a');
        $text = $smallRatesBox->eq(3)->text();
        preg_match("/(\d+)/i", $text, $matches);
        return (int)$matches[0];
    }

    /**
     * @return Url
     */
    public function getShortestTitle()
    {
        $shortestTitle = false;
        $this->filter('div.download > dl span.n')->each(function (Crawler $node) use (&$shortestTitle) {
            $text = $node->text();
            if (!$shortestTitle || strlen($shortestTitle) > strlen($text)) {
                $shortestTitle = $text;
            }
        });
        return $shortestTitle;
    }

    public function getTorrentHash()
    {
        $text = $this->filter('div.trackers > div')->text();
        $hash = str_replace('info_hash: ', '', $text);
        return $hash;
    }

}