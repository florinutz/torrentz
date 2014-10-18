<?php

namespace Flo\Torrentz\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Flo\Torrentz\Entity\AbstractLog;
use Flo\Torrentz\Entity\SearchLog;

class SearchLogRepository extends EntityRepository
{

    /**
     * @param int $n the limit
     * @return mixed
     */
    public function getSearchesForNextCron($n)
    {
        $qb = $this->createQueryBuilder('l')
            ->leftJoin('l.search', 's')
            ->select('s')
            ->orderBy('l.date', 'DESC')
            ->setMaxResults($n)
        ;
        return $qb->getQuery();
    }

}