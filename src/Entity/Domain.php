<?php
// florin 9/4/14 5:03 PM

namespace Flo\Torrentz\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @Table(name="domains")
 * @Entity(repositoryClass="Flo\Torrentz\Entity\Repository\DomainRepository")
 * @HasLifecycleCallbacks()
 */
class Domain
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
    }

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    protected $name;

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

    /**
     * Set name
     *
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

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

}
