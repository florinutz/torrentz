<?php
// florin 9/4/14 5:03 PM

namespace Flo\Torrentz\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flo\Torrentz\Entity\SearchLog;
use Flo\Torrentz\Entity\TorrentLog;

/**
 * Search
 *
 * @Entity
 * @Table(name="logs")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discriminator", type="smallint")
 * @DiscriminatorMap({"1" = "SearchLog", "2" = "TorrentLog", 3 = "UrlLog"})
 * @HasLifecycleCallbacks()
 */
abstract class AbstractLog
{
    /**
     * @var integer
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     * @Column(name="duration", nullable=true, type="integer")
     */
    protected $duration;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime")
     */
    protected $date;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getDate()->format('d-m-Y');
    }


    /**
     * @PrePersist @PreUpdate
     */
    public function updateCreatedAt()
    {
        if (!$this->getDate()) {
            $this->setDate(new \DateTime('now'));
        }
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Search
     */
    public function setDate($createdAt)
    {
        $this->date = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Set duration
     *
     * @param double $duration
     * @return self
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return double
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
