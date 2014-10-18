<?php
// florin 9/4/14 5:03 PM

namespace Flo\Torrentz\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Flo\Torrentz\Entity\Url;
use Flo\Torrentz\Entity\Torrent;

/**
 * Search
 *
 * @Table(name="log_url")
 * @Entity(repositoryClass="Flo\Torrentz\Entity\Repository\UrlLogRepository")
 */
class UrlLog extends AbstractLog
{

    /**
     * @param null $duration
     * @param \DateTime $date
     * @param Url $url
     */
    function __construct($duration=null, \DateTime $date=null, Url $url=null)
    {
        if ($duration) {
            $this->setDuration($duration);
        }

        if ($date) {
            $this->setDate($date);
        }
        else {
            $this->setDate(new \DateTime());
        }

        if ($url) {
            $this->setUrl($url);
        }
    }

    /**
     * @ManyToOne(targetEntity="Url", inversedBy="runs")
     * @JoinColumn(name="url_id", referencedColumnName="id", nullable=false)
     */
    private $url;

    /**
     * Set url
     *
     * @param Url $url
     * @return self
     */
    public function setUrl(Url $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return Url 
     */
    public function getUrl()
    {
        return $this->url;
    }
}
