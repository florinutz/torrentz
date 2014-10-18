<?php
// florin 9/4/14 5:03 PM

namespace Flo\Torrentz\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Url
 *
 * @Table(name="urls")
 * @Entity(repositoryClass="Flo\Torrentz\Entity\Repository\UrlRepository")
 * @HasLifecycleCallbacks()
 */
class Url
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Torrent", inversedBy="urls")
     * @JoinColumn(name="torrent_id", referencedColumnName="id", nullable=false)
     */
    protected $torrent;

    /**
     * @ManyToOne(targetEntity="Domain")
     * @JoinColumn(name="domain_id", referencedColumnName="id", nullable=true)
     */
    protected $domain;

    /**
     * @var string
     *
     * @Column(name="title", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @Column(name="url", type="string", length=255, nullable=false, unique=true)
     */
    protected $url;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="UrlLog", mappedBy="url")
     **/
    private $runs;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Url
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->runs = new ArrayCollection();
    }

    /**
     * Set torrent
     *
     * @param Torrent $torrent
     * @return Url
     */
    public function setTorrent(Torrent $torrent)
    {
        $this->torrent = $torrent;

        return $this;
    }

    /**
     * Get torrent
     *
     * @return Torrent 
     */
    public function getTorrent()
    {
        return $this->torrent;
    }

    /**
     * Add runs
     *
     * @param UrlLog $runs
     * @return Url
     */
    public function addRun(UrlLog $runs)
    {
        $this->runs[] = $runs;

        return $this;
    }

    /**
     * Remove runs
     *
     * @param UrlLog $runs
     */
    public function removeRun(UrlLog $runs)
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
     * Set title
     *
     * @param string $title
     * @return Url
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
     * Set date
     *
     * @param \DateTime $date
     * @return Url
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Url
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
     * @PrePersist @PreUpdate
     */
    public function updateTimestamps()
    {
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * Set domain
     *
     * @param Domain $domain
     * @return Url
     */
    public function setDomain(Domain $domain = null)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return Domain 
     */
    public function getDomain()
    {
        return $this->domain;
    }
}
