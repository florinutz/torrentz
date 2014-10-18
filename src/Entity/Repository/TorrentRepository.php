<?php

namespace Flo\Torrentz\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Query;
use Flo\Torrentz\Entity\Torrent;
use Flo\Torrentz\Entity\Tag;
use Flo\Torrentz\Prioritizer\Queue;

class TorrentRepository extends EntityRepository
{
    /**
     * @param Tag|string $tag
     * @throws \Doctrine\ORM\EntityNotFoundException if tag doesn't exist
     * @throws \Doctrine\ORM\ORMInvalidArgumentException if argument is not a tag instance or a string
     * @return int
     */
    public function countTorrentsTaggedAs($tag)
    {
        $tagRepo = $this->getEntityManager()->getRepository('DubiosTorrentzCrawlerBundle:Tag');
        if (is_string($tag)) {
            if (!$tag = $tagRepo->findOneBy(['name' => $tag])) {
                throw new EntityNotFoundException;
            }
        }
        if ($tag instanceof Tag) {
            $dql = "SELECT COUNT(t.id) AS cnt FROM {$this->getEntityName()} t JOIN t.tags tag WHERE tag.id = :id";
            $query = $this->getEntityManager()->createQuery($dql)->setParameter('id', $tag->getId());
            return $query->getResult(Query::HYDRATE_SINGLE_SCALAR);
        }
        else {
            throw new ORMInvalidArgumentException('Invalid input tag');
        }
    }

    /**
     * Returns first torrent in need of attention
     *
     * @param int $howMany
     * @return Torrent
     */
    public function getSubjects($howMany=1)
    {
        $dql = "SELECT t FROM {$this->getEntityName()} t LEFT JOIN t.runs r WHERE r.id IS NULL ORDER BY t.created_at ASC";
        $query = $this->getEntityManager()->createQuery($dql)->setMaxResults($howMany);
        return $query->getResult();
    }

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
		$dql = "SELECT e as entity, count(e) as $numberOfCrawlsStr, max(log.date) as $lastParseStr FROM $thisClass e LEFT OUTER JOIN DubiosTorrentzCrawlerBundle:TorrentLog log WITH log.torrent = e GROUP BY e ORDER BY log.date ASC";
		$result = $this->getResultFromDql($dql, $n);
		foreach ( $result as &$r ) {
			if (!$r[$lastParseStr]) {
				$r = $r['entity'];
			}
		}
		return $result;
	}
}