<?php
// florin 9/4/14 5:03 PM

namespace Flo\Torrentz\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tag
 *
 * @Table(name="tags")
 * @Entity(repositoryClass="Flo\Torrentz\Entity\Repository\TagRepository")
 * @HasLifecycleCallbacks()
 */
class Tag
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    function __construct($name = null)
    {
        if ($name) {
            $this->setName($name);
        }
        $this->torrents = new ArrayCollection();
    }

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var int
     * @Column(name="torrents_number", nullable=true, type="integer")
     */
    protected $torrentsNumber = 0;

    /**
     * @ManyToMany(targetEntity="Torrent", mappedBy="tags")
     */
    protected $torrents;

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
     * @PrePersist @PreUpdate
     */
    public function updateTimestamps()
    {
        $this->updateUpdatedAt();
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * @PreUpdate
     */
    public function updateUpdatedAt()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function formatName($name)
    {
        $name = str_replace([' ', '_', ',', '*'], '-', $name);
        return trim(strtolower($name));
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $this->formatName($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Tag
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
     * @return Tag
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
     * @param $str
     * @return bool
     */
    public function matchesName($str)
    {
        return trim(strtolower($this->getName())) == trim(strtolower($str));
    }

    /**
     * Set number of torrents tagged with this tag
     *
     * @param integer $torrentsNumber
     * @return Tag
     */
    public function setTorrentsNumber($torrentsNumber)
    {
        $this->torrentsNumber = $torrentsNumber;

        return $this;
    }

    /**
     * Get number of torrents tagged with this tag
     *
     * @return integer 
     */
    public function getTorrentsNumber()
    {
        return $this->torrentsNumber;
    }

    /**
     * Add torrents
     *
     * @param Torrent $torrents
     * @return Tag
     */
    public function addTorrent(Torrent $torrents)
    {
        $this->torrents[] = $torrents;

        return $this;
    }

    /**
     * Remove torrents
     *
     * @param Torrent $torrents
     */
    public function removeTorrent(Torrent $torrents)
    {
        $this->torrents->removeElement($torrents);
    }

    /**
     * Get torrents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTorrents()
    {
        return $this->torrents;
    }
}
