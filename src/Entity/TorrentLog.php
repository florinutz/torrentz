<?php
// florin 9/4/14 5:03 PM

namespace Flo\Torrentz\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flo\Torrentz\Entity\Torrent;

/**
 * Search
 *
 * @Table(name="log_torrent")
 * @Entity(repositoryClass="Flo\Torrentz\Entity\Repository\TorrentLogRepository")
 */
class TorrentLog extends AbstractLog
{
    /**
     * @param null $duration
     * @param \DateTime $date
     * @param Torrent $torrent
     */
    function __construct($duration=null, \DateTime $date=null, Torrent $torrent=null)
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

        if ($torrent) {
            $this->setTorrent($torrent);
        }
    }

    /**
     * @ManyToOne(targetEntity="Torrent", inversedBy="runs")
     * @JoinColumn(name="torrent_id", referencedColumnName="id", nullable=false)
     */
    private $torrent;

    /**
     * Set torrent
     *
     * @param Torrent $torrent
     * @return self
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

}
