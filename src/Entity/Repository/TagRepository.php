<?php

namespace Flo\Torrentz\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Flo\Torrentz\Entity\Tag;

class TagRepository extends EntityRepository
{

    public function isNameAmongTags($name, $tags = null)
    {
        if (!$tags) {
            $tags = $this->findAll();
        }
        foreach ($tags as $tag) {
            /** @var Tag $tag */
            if ($tag->matchesName($name)) {
                return $tag;
            }
        }
        return false;
    }

}
