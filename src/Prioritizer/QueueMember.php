<?php
// florin 9/24/14 11:19 AM
namespace Flo\Torrentz\Prioritizer;

use Flo\Torrentz\Entity\Search;
use Flo\Torrentz\Entity\Torrent;

class QueueMember
{
    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @var Priority
     */
    protected $priority;

    function __construct( $entity, Priority $priority )
    {
        $this->setEntity($entity);
        $this->setPriority($priority);
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity( $entity )
    {
        $this->entity = $entity;
    }

    /**
     * @return Priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param Priority $priority
     */
    public function setPriority( Priority $priority )
    {
        $this->priority = $priority;
    }

    public function getJobsCommand()
    {
        $entity = $this->getEntity();
        if ($entity instanceof Search) {
            return 'torrentz:search';
        }
        elseif ($entity instanceof Torrent) {
            return 'torrentz:torrent';
        }
        throw new \Exception('Unknown entity type');
    }

    public function getJobsArgs()
    {
        $entity = $this->getEntity();
        if ($entity instanceof Search) {
            return [ 'http://torrentz.com/' . $entity->getQuery() ];
        }
        elseif ($entity instanceof Torrent) {
            return [ $entity->getHash() ];
        }
        throw new \Exception('Unknown entity type');
    }

}