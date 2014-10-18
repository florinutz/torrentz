<?php
// florin 9/4/14 5:03 PM

namespace Flo\Torrentz\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flo\Torrentz\Entity\Search;

/**
 * Search
 *
 * @Table(name="log_search")
 * @Entity(repositoryClass="Flo\Torrentz\Entity\Repository\SearchLogRepository")
 */
class SearchLog extends AbstractLog
{
    /**
     * @param Search $search the search entry being logged
     * @param null $duration request duration
     * @param \DateTime $date date this was initiated
     * @param null $results number of results returned by torrentz
     */
    function __construct($duration=null, \DateTime $date=null, Search $search=null, $results=null)
    {
        if ($duration) {
            $this->setDuration($duration);
        }

        if ($date) {
            $this->setDate($date);
        }
        else {
            $this->setDate(new \DateTime());
        }

        if ($search) {
            $this->setSearch($search);
        }

        if ($results) {
            $this->setResults($results);
        }
    }

    /**
     * @ManyToOne(targetEntity="Search", inversedBy="runs")
     * @JoinColumn(name="search_id", referencedColumnName="id", nullable=false)
     */
    private $search;

    /**
     * @var int
     * @Column(name="results", nullable=true, type="integer")
     */
    private $results;

    /**
     * Set search
     *
     * @param Search $search
     * @return self
     */
    public function setSearch(Search $search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get search
     *
     * @return Search
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set results
     *
     * @param integer $results
     * @return SearchLog
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get results
     *
     * @return integer 
     */
    public function getResults()
    {
        return $this->results;
    }
}
