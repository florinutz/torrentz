<?php
// florin 9/22/14 3:27 PM
namespace Flo\Torrentz\Prioritizer;

use Flo\Torrentz\Entity\Search;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \ArrayObject as ArrayObject;

class Queue extends ArrayObject implements ContainerAwareInterface, LoggerAwareInterface
{
    const STR_NUMBER_OF_CRAWLS = 'number_of_crawls';
    const STR_LAST_CRAWL = 'last_crawl';
    const DEFAULT_FREQUENCY = 14400;

    const RETRIEVE_ALL = 1;
    const RETRIEVE_ENTITIES = 2;
    const RETRIEVE_PRIORITIES = 3;

    /**
     * @param mixed $index
     * @param QueueMember $newVal
     */
    public function offsetSet( $index, $newVal )
    {
        if (!($newVal instanceof QueueMember)) {
            throw new \InvalidArgumentException('All queue members must be instances of QueueMember');
        }
        $key = $newVal->getEntity() instanceof Search ? 'search' : 'torrent';
        $index = $index ? $index : $key . $newVal->getEntity()->getId();
        parent::offsetSet( $index, $newVal );
        $this->uasort([ $this, 'compare' ]);
    }

    public function compare(QueueMember $member1, QueueMember $member2)
    {
        return $member1->getPriority()->compare( $member2->getPriority() );
    }

    /**
     * @return array
     */
    public function toArray($retrieve = self::RETRIEVE_ALL)
    {
        $arr = $this->getArrayCopy();
        if ($retrieve != self::RETRIEVE_ALL) {
            $result = [];
            foreach ($arr as $member) {
                /** @var $member QueueMember */
                if ($retrieve == self::RETRIEVE_ENTITIES) {
                    $result[] = $member->getEntity();
                }
                elseif ($retrieve == self::RETRIEVE_PRIORITIES) {
                    $result[] = $member->getPriority();
                }
                else {
                    throw new \InvalidArgumentException('Unknown retrieve');
                }
            }
            return $result;
        }
        return $arr;
    }

    //region aux

    /** @var ContainerInterface */
    protected $container;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger( LoggerInterface $logger )
    {
        $this->logger = $logger;
    }

    //endregion
}