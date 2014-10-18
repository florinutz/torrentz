<?php

namespace Flo\Torrentz\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Flo\Torrentz\Entity\Url;

/**
 * UrlRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UrlRepository extends EntityRepository
{
    public function alreadyExists(Url $url)
    {
        if ($existing = $this->findOneBy(['url' => $url->getUrl()])) {
            return $existing;
        }
        return false;
    }
}
