<?php
// florin, 11/14/14, 6:07 PM
namespace Flo\Torrentz\Test\Crawler;

use Flo\Torrentz\Crawler\TorrentCrawler;

class TorrentCrawlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TorrentCrawler */
    protected $crawler;

    protected function setUp()
    {
        $html = file_get_contents(__DIR__ . '/../data/torrent.html');
        $this->crawler = new TorrentCrawler($html);
    }

    public function testSeeders()
    {
        $shouldBe = 430;
        $is = $this->crawler->getSeeders();
        $this->assertEquals($shouldBe, $is, 'Seeders number ok');
        $this->assertInternalType("int", $is, "Seeders number is integer");
    }

    public function testDate()
    {
        $shouldBe = new \DateTime('Sat, 17 Mar 2012 00:25:37');
        $is = $this->crawler->getDate();
        $this->assertEquals($shouldBe, $is, 'Time is ok');
        $this->assertInstanceOf("\DateTime", $is, "Time is DateTime");
    }

    public function testHash()
    {
        $shouldBe = 'dad2e3aaabd54ff0a63e7b6c786c5532f0d5bd18';
        $is = $this->crawler->getTorrentHash();
        $this->assertEquals($shouldBe, $is, 'Hash is ok');
        $this->assertInternalType("string", $is, "Hash is string");
    }

    public function testRates()
    {
        $shouldBe = ['rate' => 7, 'by' => 38];
        $is = $this->crawler->getGoodRate();
        $this->assertEquals($shouldBe, $is, 'Good rate is ok');
        $this->assertInternalType("array", $is, "Good rate is array");

        $shouldBe = 0;
        $is = $this->crawler->getFakeRate();
        $this->assertEquals($shouldBe, $is, 'Fake rate is ok');
        $this->assertInternalType("int", $is, "Fake rate is integer");

        $shouldBe = 0;
        $is = $this->crawler->getPasswordRate();
        $this->assertEquals($shouldBe, $is, 'Password rate is ok');
        $this->assertInternalType("int", $is, "Password rate is integer");

        $shouldBe = 8;
        $is = $this->crawler->getLowQualityRate();
        $this->assertEquals($shouldBe, $is, 'Low quality rate is ok');
        $this->assertInternalType("int", $is, "Low quality rate is integer");

        $shouldBe = 0;
        $is = $this->crawler->getVirusRate();
        $this->assertEquals($shouldBe, $is, 'Virus rate is ok');
        $this->assertInternalType("int", $is, "Virus rate is integer");
    }

    public function testTitle()
    {
        $is = $this->crawler->getTitle();
        $shouldBe = 'Titanic (1997) [1080p]';
        $this->assertEquals($shouldBe, $is, 'Title is ok');
        $this->assertInternalType("string", $is, "Title is string");
    }

    public function testShortestTitle()
    {
        $is = $this->crawler->getShortestTitle();
        $shouldBe = 'Titanic 1997';
        $this->assertEquals($shouldBe, $is, 'Shortest title is ok');
        $this->assertInternalType("string", $is, "Shortest title is string");
    }

    public function testSize()
    {
        $is = $this->crawler->getSize();
        $shouldBe = 2242514811;
        $this->assertEquals($shouldBe, $is, 'Size is ok');
        $this->assertInternalType("int", $is, "Size is integer");
    }

    public function testLeechers()
    {
        $is = $this->crawler->getLeechers();
        $shouldBe = 84;
        $this->assertEquals($shouldBe, $is, 'Leechers number is ok');
        $this->assertInternalType("int", $is, "Leechers number is integer");
    }

}