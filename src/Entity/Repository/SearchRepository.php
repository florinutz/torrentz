<?php

namespace Flo\Torrentz\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Flo\Torrentz\Entity\Search;
use Flo\Torrentz\Prioritizer\Queue;

class SearchRepository extends EntityRepository
{
    private function getResultFromDql($dql, $n=null)
    {
        $query = $this->getEntityManager()->createQuery($dql);
        if ($n) {
            $query->setMaxResults($n);
        }
        return $query->getResult();
    }

	/**
	 * @param int $n limit
	 *
	 * @return array|null
	 */
    public function getUrgentsNotParsedFirst($n)
    {
        $numberOfCrawlsStr = Queue::STR_NUMBER_OF_CRAWLS;
        $lastParseStr = Queue::STR_LAST_CRAWL;
        $thisClass = $this->getClassName();
        $dql = "SELECT e as entity, count(e) as $numberOfCrawlsStr, max(log.date) as $lastParseStr FROM $thisClass e LEFT OUTER JOIN DubiosTorrentzCrawlerBundle:SearchLog log WITH log.search = e GROUP BY e ORDER BY log.date ASC";
        $result = $this->getResultFromDql($dql, $n);
	    foreach ( $result as &$r ) {
		    if (!$r[$lastParseStr]) {
			    $r = $r['entity'];
		    }
	    }
	    return $result;
    }

	/**
	 * @param $result
	 * @param $lastParseStr
	 * @param $numberOfCrawlsStr
	 *
	 * @return Queue
	 */
	public function queueResults( $result, $lastParseStr, $numberOfCrawlsStr ) {
		$queue   = new Queue();
		$queue->setExtractFlags( \SplPriorityQueue::EXTR_BOTH );
		foreach ( $result as $r ) {
			$r[ $numberOfCrawlsStr ] = $r[ $lastParseStr ] ? (int) $r[ $numberOfCrawlsStr ] : 0;
			/** @var Search $entity */
			$entity = $r['entity'];
			$queue->insert( $entity, [
				$numberOfCrawlsStr  => $r[ $numberOfCrawlsStr ],
				$lastParseStr       => $r[ $lastParseStr ],
				self::DQL_FREQUENCY => $entity->getFrequency()
			]);
		}

		return $queue;
	}

}