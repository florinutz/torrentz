<?php
// florin, 11/14/14, 6:07 PM
namespace Flo\Torrentz\Test\Crawler;

use Flo\Torrentz\Crawler\SearchCrawler;

class SearchCrawlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var SearchCrawler */
    protected $crawler;

    protected function setUp()
    {
	    $sourceLocation = __DIR__ . '/../data/movie_search.html';
	    $html = file_get_contents( $sourceLocation );
        $this->crawler = new SearchCrawler($html);
    }

    public function testNumberOfSearchResults()
    {
        $is = $this->crawler->getNumberOfSearchResults();
        $shouldBe = 716459;
        $this->assertEquals($shouldBe, $is, 'Results number is ok');
        $this->assertInternalType("int", $is, "Results number is integer");
    }

}