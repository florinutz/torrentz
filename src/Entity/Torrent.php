<?php
// florin 9/4/14 5:03 PM

namespace Flo\Torrentz\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Flo\Torrentz\Entity\Repository\TorrentRepository;

/**
 * Torrent
 *
 * @Table(name="torrents")
 * @Entity(repositoryClass="Flo\Torrentz\Entity\Repository\TorrentRepository")
 * @HasLifecycleCallbacks()
 */
class Torrent
{
    //1d
    const DEFAULT_FREQUENCY = 86400;

    /**
     * @var integer
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="TorrentLog", mappedBy="torrent")
     **/
    private $runs;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="Url", mappedBy="torrent")
     **/
    private $urls;

    /**
     * @var string
     *
     * @Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @Column(name="shortest_title", type="string", length=255, nullable=true)
     */
    protected $shortestTitle;

    /**
     * @var string
     *
     * @Column(name="hash", type="string", length=45, nullable=false, unique=true)
     */
    protected $hash;

	/**
	 * @var int
	 * @Column(name="frequency", nullable=true, type="integer")
	 */
	private $frequency =  self::DEFAULT_FREQUENCY;

    /**
     * @var int
     * @Column(name="rating", nullable=true, type="integer")
     */
    protected $rating;

    /**
     * @var int
     * @Column(name="rated_by", nullable=true, type="integer")
     */
    protected $ratedBy;

    /**
     * @var float
     * @Column(name="size", nullable=false, type="integer")
     */
    protected $size;

    /**
     * @var int
     * @Column(name="peers", nullable=true, type="integer")
     */
    protected $peers;

    /**
     * @var int
     * @Column(name="leechers", nullable=true, type="integer")
     */
    protected $leechers;

    /**
     * @var int
     * @Column(name="rate_fake", nullable=true, type="smallint")
     */
    protected $fakeRate;

    /**
     * @var int
     * @Column(name="rate_password", nullable=true, type="smallint")
     */
    protected $passwordRate;

    /**
     * @var int
     * @Column(name="rate_low_quality", nullable=true, type="smallint")
     */
    protected $lowQualityRate;

    /**
     * @var int
     * @Column(name="rate_virus", nullable=true, type="smallint")
     */
    protected $virusRate;

    /**
     * @var ArrayCollection
     *
     * @ManyToMany(targetEntity="Tag", inversedBy="torrents")
     * @JoinTable(
     *      name="torrent_tags",
     *      joinColumns={@JoinColumn(name="torrent_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id")}
     *   )
     */
    protected $tags;

    /**
     * @var \DateTime
     *
     * @Column(name="date", nullable=true, type="datetime")
     */
    protected $date;


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
        return $this->getTitle();
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
     * Set title
     *
     * @param string $title
     * @return Torrent
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Torrent
     */
    public function setHash($hash)
    {
        if (!preg_match('/(\w{40})/', $hash, $matches)) {
            throw new \InvalidArgumentException('Invalid $hash string');
        }

        $this->hash = $matches[1];

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Torrent
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Torrent
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set size
     *
     * @param float $size
     * @return Torrent
     */
    public function setSize($size)
    {
        if (is_string($size)) {
            $size = $this->getSizeInBytes($size);
        }
        $this->size = $size;
        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set peers
     *
     * @param integer $peers
     * @return Torrent
     */
    public function setPeers($peers)
    {
        $this->peers = $peers;

        return $this;
    }

    /**
     * Get peers
     *
     * @return integer 
     */
    public function getPeers()
    {
        return $this->peers;
    }

    /**
     * Set leechers
     *
     * @param integer $leechers
     * @return Torrent
     */
    public function setLeechers($leechers)
    {
        $this->leechers = $leechers;

        return $this;
    }

    /**
     * Get leechers
     *
     * @return integer 
     */
    public function getLeechers()
    {
        return $this->leechers;
    }

    /**
     * @PrePersist @PreUpdate
     */
    public function assertHashIsValid()
    {
        if (strlen($this->getHash()) != 40) {
            throw new \Exception('Hash is not 40 characters long, so it\'s invalid.');
        }
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Torrent
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
     * @return Torrent
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
     * @param Torrent $torrent
     * @return bool
     */
    public function updateFrom(self $torrent)
    {
        // todo update tags?
        if ($this->needsUpdateFrom($torrent)) {
            $this->setPeers($torrent->getPeers());
            $this->setLeechers($torrent->getLeechers());
            $this->setRating($torrent->getRating());
            return true;
        }
        return false;
    }

    /**
     * @param Torrent $torrent
     * @return bool
     */
    public function needsUpdateFrom(self $torrent)
    {
        if ($torrent->getPeers() != $this->getPeers()) {
            return true;
        }
        if ($torrent->getLeechers() != $this->getLeechers()) {
            return true;
        }
        if ($torrent->getRating() != $this->getRating()) {
            return true;
        }
        return false;
    }

    public function getSizeInBytes($str) {
        $matches = [];
        preg_match('/(?P<value>[1-9]\d*)\s*(?P<order>[kmgtpezy]?b)?/i', $str, $matches);
        if (isset($matches['value']) && is_numeric($matches['value'])) {
            $value = $matches['value'];
            $order = isset($matches['order']) ? strtoupper($matches['order']) : 'MB';
            $possibleOrders = ['B'=>0, 'KB'=>1, 'MB'=>2, 'GB'=>3, 'TB'=>4, 'PB'=>5, 'EB'=>6, 'ZB'=>7, 'YB'=>8];
            if (array_key_exists($order, $possibleOrders)) {
                $power = $possibleOrders[$order];
                return $value * pow(1024, $power);
            }
        }
        return false;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->runs = new ArrayCollection;
        $this->urls = new ArrayCollection;
        $this->tags = new ArrayCollection;
    }

    /**
     * Add runs
     *
     * @param TorrentLog $runs
     * @return Torrent
     */
    public function addRun(TorrentLog $runs)
    {
        $this->runs[] = $runs;

        return $this;
    }

    /**
     * Remove runs
     *
     * @param TorrentLog $runs
     */
    public function removeRun(TorrentLog $runs)
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
     * Add urls
     *
     * @param Url $urls
     * @return Torrent
     */
    public function addUrl(Url $urls)
    {
        $this->urls[] = $urls;

        return $this;
    }

    /**
     * Remove urls
     *
     * @param Url $urls
     */
    public function removeUrl(Url $urls)
    {
        $this->urls->removeElement($urls);
    }

    /**
     * Get urls
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * Add tags
     *
     * @param Tag $tag
     * @return Torrent
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param Tag $tags
     */
    public function removeTag(Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set ratedBy
     *
     * @param integer $ratedBy
     * @return Torrent
     */
    public function setRatedBy($ratedBy)
    {
        $this->ratedBy = $ratedBy;

        return $this;
    }

    /**
     * Get ratedBy
     *
     * @return integer 
     */
    public function getRatedBy()
    {
        return $this->ratedBy;
    }


    /**
     * Set fakeRate
     *
     * @param integer $fakeRate
     * @return Torrent
     */
    public function setFakeRate($fakeRate)
    {
        $this->fakeRate = $fakeRate;

        return $this;
    }

    /**
     * Get fakeRate
     *
     * @return integer 
     */
    public function getFakeRate()
    {
        return $this->fakeRate;
    }

    /**
     * Set passwordRate
     *
     * @param integer $passwordRate
     * @return Torrent
     */
    public function setPasswordRate($passwordRate)
    {
        $this->passwordRate = $passwordRate;

        return $this;
    }

    /**
     * Get passwordRate
     *
     * @return integer 
     */
    public function getPasswordRate()
    {
        return $this->passwordRate;
    }

    /**
     * Set lowQualityRate
     *
     * @param integer $lowQualityRate
     * @return Torrent
     */
    public function setLowQualityRate($lowQualityRate)
    {
        $this->lowQualityRate = $lowQualityRate;

        return $this;
    }

    /**
     * Get lowQualityRate
     *
     * @return integer 
     */
    public function getLowQualityRate()
    {
        return $this->lowQualityRate;
    }

    /**
     * Set virusRate
     *
     * @param integer $virusRate
     * @return Torrent
     */
    public function setVirusRate($virusRate)
    {
        $this->virusRate = $virusRate;

        return $this;
    }

    /**
     * Get virusRate
     *
     * @return integer 
     */
    public function getVirusRate()
    {
        return $this->virusRate;
    }


    /**
     * Set shortestTitle
     *
     * @param string $shortestTitle
     * @return Torrent
     */
    public function setShortestTitle($shortestTitle)
    {
        $this->shortestTitle = $shortestTitle;

        return $this;
    }

    /**
     * Get shortestTitle
     *
     * @return string 
     */
    public function getShortestTitle()
    {
        return $this->shortestTitle;
    }

    /**
     * Set frequency
     *
     * @param integer $frequency
     * @return Torrent
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
}
