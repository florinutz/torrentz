<?php

namespace Flo\Torrentz\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Flo\Torrentz\Entity\Tag;

class TagRepository extends EntityRepository
{

    public function isNameAmongTags($name, $tags=[])
    {
        foreach ($tags as $tag) {
            /** @var Tag $tag */
            if ($tag->matchesName($name)) {
                return $tag;
            }
        }
        return false;
    }

    function getTagObjects($tags)
    {
        $result = [];
        $existingTags = $this->createQueryBuilder('t')
            ->where('t.name in (:tags)')
            ->setParameter('tags', $tags)
            ->getQuery()
            ->execute();
        foreach ($tags as $tagName) {
            if (!$tag = $this->isNameAmongTags($tagName, $existingTags)) {
                $tag = new Tag($tagName);
                $this->getEntityManager()->persist($tag);
                $this->getEntityManager()->flush($tag);
            }
            $result[] = $tag;
        }
        return $result;
    }
}
