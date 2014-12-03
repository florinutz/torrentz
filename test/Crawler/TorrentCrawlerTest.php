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
	    // it's easier to just update the source of this hash: 2db882772a559095ae637e42a41f003f601d06af
	    // than to change the torrent completely
        $html = file_get_contents(__DIR__ . '/../data/torrent.html');
        $this->crawler = new TorrentCrawler($html);
    }

    public function testSeeders()
    {
        $shouldBe = 16227;
        $is = $this->crawler->getSeeders();
        $this->assertEquals($shouldBe, $is, 'Seeders number ok');
        $this->assertInternalType("int", $is, "Seeders number is integer");
    }

    public function testDate()
    {
        $shouldBe = new \DateTime('2014-11-27T19:24:03+0200');
        $is = $this->crawler->getDate();
        $this->assertEquals($shouldBe, $is, 'Time is not ok');
        $this->assertInstanceOf("\DateTime", $is, "Time is DateTime");
    }

    public function testHash()
    {
        $shouldBe = '2db882772a559095ae637e42a41f003f601d06af';
        $is = $this->crawler->getTorrentHash();
        $this->assertEquals($shouldBe, $is, 'Hash is not ok');
        $this->assertInternalType("string", $is, "Hash is string");
    }

    public function testRates()
    {
        $shouldBe = ['rate' => 5, 'by' => 71];
        $is = $this->crawler->getGoodRate();
        $this->assertEquals($shouldBe, $is, 'Good rate is not ok');
        $this->assertInternalType("array", $is, "Good rate is not an array");

        $shouldBe = 36;
        $is = $this->crawler->getFakeRate();
        $this->assertEquals($shouldBe, $is, 'Fake rate is not ok');
        $this->assertInternalType("int", $is, "Fake rate is not an integer");

        $shouldBe = 0;
        $is = $this->crawler->getPasswordRate();
        $this->assertEquals($shouldBe, $is, 'Password rate is not ok');
        $this->assertInternalType("int", $is, "Password rate is integer");

        $shouldBe = 0;
        $is = $this->crawler->getLowQualityRate();
        $this->assertEquals($shouldBe, $is, 'Low quality rate is not ok');
        $this->assertInternalType("int", $is, "Low quality rate is integer");

        $shouldBe = 0;
        $is = $this->crawler->getVirusRate();
        $this->assertEquals($shouldBe, $is, 'Virus rate is not ok');
        $this->assertInternalType("int", $is, "Virus rate is integer");
    }

    public function testTitle()
    {
        $is = $this->crawler->getTitle();
        $shouldBe = 'Fury.2014.DVDSCR.X264.AC3-Blackjesus';
        $this->assertEquals($shouldBe, $is, 'Title is not ok');
        $this->assertInternalType("string", $is, "Title is string");
    }

    public function testShortestTitle()
    {
        $is = $this->crawler->getShortestTitle();
        $shouldBe = 'Fury.2014.DVDSCR.X264.AC3-Blackjesus';
        $this->assertEquals($shouldBe, $is, 'Shortest title is not ok');
        $this->assertInternalType("string", $is, "Shortest title is string");
    }

    public function testSize()
    {
        $is = $this->crawler->getSize();
        $shouldBe = 1214012180;
        $this->assertEquals($shouldBe, $is, 'Size is not ok');
        $this->assertInternalType("int", $is, "Size is integer");
    }

    public function testLeechers()
    {
        $is = $this->crawler->getLeechers();
        $shouldBe = 3706;
        $this->assertEquals($shouldBe, $is, 'Leechers number is not ok');
        $this->assertInternalType("int", $is, "Leechers number is integer");
    }

}