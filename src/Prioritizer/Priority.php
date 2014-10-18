<?php
// florin 9/24/14 12:47 PM
namespace Flo\Torrentz\Prioritizer;

/**
 * Class Priority
 * @package Flo\Torrentz\Prioritizer
 */
class Priority
{
    /**
     * @var int
     */
    protected $frequency;

    /**
     * @var \DateTime
     */
    protected $lastCrawl;

    /**
     * @var int
     */
    protected $numberOfCrawls;

    /**
     * @param $frequency int
     * @param $lastCrawlDate \DateTime|string
     * @param $numberOfCrawls int
     */
    function __construct( $numberOfCrawls = 0, $lastCrawlDate = null, $frequency = Queue::DEFAULT_FREQUENCY )
    {
        if ($frequency) {
            $frequency = $this->validateNumber( $frequency );
        }
        $this->setFrequency( $frequency );

        if ($lastCrawlDate) {
            $this->setLastCrawl($lastCrawlDate);
        }

        if ($numberOfCrawls) {
            $this->setNumberOfCrawls($this->validateNumber($numberOfCrawls));
        }
    }

    /**
     * @param $n
     *
     * @return int
     */
    private function validateNumber($n)
    {
        if (is_numeric($n)) {
            return (int)$n;
        }
        throw new \InvalidArgumentException("$n should be a number");
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency( $frequency = null )
    {
        $this->frequency = $frequency;
    }

    /**
     * @return mixed
     */
    public function getLastCrawl()
    {
        return $this->lastCrawl;
    }

    /**
     * @param mixed $lastCrawl
     */
    public function setLastCrawl( $lastCrawl )
    {
        if (is_string($lastCrawl)) {
            $lastCrawl = new \DateTime($lastCrawl);
        }
        elseif (!($lastCrawl instanceof \DateTime)) {
            throw new \InvalidArgumentException('Invalid date');
        }
        $this->lastCrawl = $lastCrawl;
    }

    /**
     * @return mixed
     */
    public function getNumberOfCrawls()
    {
        return $this->numberOfCrawls;
    }

    /**
     * @param mixed $numberOfCrawls
     */
    public function setNumberOfCrawls( $numberOfCrawls )
    {
        $this->numberOfCrawls = $numberOfCrawls;
    }

    /**
     * todo improve
     * @param Priority $other
     * @return int positive integer if $this is greater than $other, 0 if they are equal, negative integer otherwise
     */
    public function compare(self $other)
    {
        if (!$date1 = $this->getLastCrawl()) { //this was never crawled, so it has priority over $other
            return 1;
        }
        if (!$date2 = $other->getLastCrawl()) { //other was never crawled, so it has priority over $this
            return -1;
        }

        $ts1 = $date1->getTimestamp() + $this->getFrequency();
        $ts2 = $date2->getTimestamp() + $other->getFrequency();

        if ($ts1 > $ts2) {
            return -1; //pr2
        }
        elseif ($ts1 == $ts2) {
            return 0; // or return the comparison of crawl numbers
        }
        else {
            return 1; //pr1
        }
    }

}