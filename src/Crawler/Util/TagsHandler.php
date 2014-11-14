<?php
// florin 9/17/14 11:48 AM
namespace Flo\Torrentz\Crawler\Util;


use Flo\Torrentz\Entity\Repository\TagRepository;
use Flo\Torrentz\Entity\Tag;
use Flo\Torrentz\Entity\Torrent;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TagsHandler implements LoggerAwareInterface, ContainerAwareInterface
{
    /**
     * Splits the tags string
     *
     * @param $tags
     * @throws \InvalidArgumentException
     * @return array|boolean
     */
    public function splitTags($tags)
    {
        if (is_array($tags)) {
            foreach ($tags as $i=>$tagNames) {
                $tags[$i] = $this->splitTags($tags);
            }
            return $tags;
        }
        if (!is_string($tags)) {
            throw new \InvalidArgumentException('Invalid tags argument');
        }
        $tags = trim($tags);
        $split = preg_split("/[\s,]+/", $tags, -1, PREG_SPLIT_NO_EMPTY/*, PREG_SPLIT_DELIM_CAPTURE*/);
        $this->logger->debug("Splitting tags string '$tags'", $split);
        return $split;
    }

    /**
     * @param $tags array array of tag names
     * @param Torrent $torrent
     * @param bool $withFlush
     * @return array of all tags associated
     */
    public function addTagsToTorrent(array $tags, Torrent $torrent, $withFlush=true)
    {
        $result = [];
        $manager = $this->container->get('doctrine')->getManager();
        /**
         * @var array $tags
         * @var TagRepository $tagRepository
         */
        $tagRepository = $manager->getRepository('DubiosTorrentzCrawlerBundle:Tag');
        $existingTags = $tagRepository->createQueryBuilder('t')
            ->where('t.name in (:tags)')
            ->setParameter('tags', $tags)
            ->getQuery()
            ->execute();
        foreach ($tags as $tagName) {
            if (!$tag = $tagRepository->isNameAmongTags($tagName, $existingTags)) {
                $tag = new Tag($tagName);
                $manager->persist($tag);
                if ($withFlush) {
                    $manager->flush($tag);
                }
            }
            $result[] = $tag;
            if (!$torrent->getTags()->contains($tag)) {
                $this->logger->debug("Adding tag '{$tag->getName()}' to torrent {$torrent->getTitle()}", [
                    'id' => $torrent->getId(),
                    'hash' => $torrent->getHash()
                ]);
                $torrent->addTag($tag);
            }
        }
        return $result;
    }

    //region container-logger
    /** @var LoggerInterface */
    protected $logger;

    /** @var ContainerInterface */
    protected $container;

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    //endregion
}