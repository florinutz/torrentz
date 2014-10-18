<?php
// florin 9/4/14 5:03 PM

namespace Flo\Torrentz\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Search
 *
 * @Table(name="searches")
 * @Entity(repositoryClass="Flo\Torrentz\Entity\Repository\SearchRepository")
 * @HasLifecycleCallbacks()
 */
class Search
{

	//4h
	const DEFAULT_FREQUENCY = 14400;

    /**
     * @var integer
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @OneToMany(targetEntity="SearchLog", mappedBy="search")
     **/
    private $runs;

    /**
     * example: /search?f=dvdrip%7Cr5%7Cbdrip%7Cbrrip%7Cdvdscreener%7C"dvd+screener"
     * @var string
     *
     * @Column(name="query", type="string", length=255, nullable=false, unique=true)
     */
    protected $query;

    /**
     * frequency of requesting for this search, in seconds. Defaults to 4h
     *
     * @var int
     * @Column(name="frequency", nullable=true, type="integer")
     */
    protected $frequency = self::DEFAULT_FREQUENCY;

    /**
     * @var boolean
     *
     * @Column(name="active", type="boolean", nullable=false)
     */
    private $active = true;

    /**
     * @var \DateTime
     *
     * @Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @Column(name="updated_at", type="datetime")
     */
    private $updated_at;

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
        return $this->getQuery();
    }


    /**
     * @PrePersist @PreUpdate
     */
    public function updateTimestamps()
    {
        $this->updatedUpdatedAt();
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * @PreUpdate
     */
    public function updatedUpdatedAt()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * Set query
     *
     * @param string $query
     * @return Search
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query
     *
     * @return string 
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Search
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Search
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->runs = new ArrayCollection();
    }

    /**
     * Add runs
     *
     * @param SearchLog $runs
     * @return Search
     */
    public function addRun(SearchLog $runs)
    {
        $this->runs[] = $runs;

        return $this;
    }

    /**
     * Remove runs
     *
     * @param SearchLog $runs
     */
    public function removeRun(SearchLog $runs)
    {
        $this->runs->removeElement($runs);
    }

    /**
     * Get runs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRuns()
    {
        return $this->runs;
    }

    /**
     * Set frequency
     *
     * @param integer $frequency
     * @return Search
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return integer 
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Search
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
}
