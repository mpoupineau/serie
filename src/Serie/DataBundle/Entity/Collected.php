<?php

namespace Serie\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Collected
 *
 * @ORM\Table(name="collected")
 * @ORM\Entity(repositoryClass="Serie\DataBundle\Repository\CollectedRepository")
 */
class Collected
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="rated", type="integer", nullable=true)
     */
    private $rated;

    /**
     * @var int
     *
     * @ORM\Column(name="seasonSeen", type="integer", nullable=true)
     */
    private $seasonSeen;
	
	/**
     * @var int
     *
     * @ORM\Column(name="episodeSeen", type="integer", nullable=true)
     */
    private $episodeSeen;

    /**
     * @var bool
     *
     * @ORM\Column(name="follow", type="boolean")
     */
    private $follow;

    /**
     * @var bool
     *
     * @ORM\Column(name="alertFirstEpisode", type="boolean")
     */
    private $alertFirstEpisode;

    /**
     * @var bool
     *
     * @ORM\Column(name="alertLastEpisode", type="boolean")
     */
    private $alertLastEpisode;

    /**
     * @var bool
     *
     * @ORM\Column(name="alertEachEpisode", type="boolean")
     */
    private $alertEachEpisode;

	/**
	* @ORM\ManyToOne(targetEntity="Serie\UserBundle\Entity\User", inversedBy="collecteds")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $user;

	/**
	* @ORM\ManyToOne(targetEntity="Serie\DataBundle\Entity\Serie", inversedBy="collecteds")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $serie;
	
	/**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
	private $comment;
	
	/**
     * @var string
     *
     * @ORM\Column(name="status", type="text")
     */
	private $status;

	/*public function __construct()
	{
	}*/
	public function __construct()
	{
		$this->follow = true;
		$this->alertEachEpisode = false;
		$this->alertFirstEpisode = false;
		$this->alertLastEpisode = false;
	}
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rated
     *
     * @param integer $rated
     *
     * @return Collected
     */
    public function setRated($rated)
    {
        $this->rated = $rated;

        return $this;
    }

    /**
     * Get rated
     *
     * @return int
     */
    public function getRated()
    {
        return $this->rated;
    }

    /**
     * Set seasonSeen
     *
     * @param integer $seasonSeen
     *
     * @return Collected
     */
    public function setSeasonSeen($seasonSeen)
    {
        $this->seasonSeen = $seasonSeen;

        return $this;
    }

    /**
     * Get seasonSeen
     *
     * @return int
     */
    public function getSeasonSeen()
    {
        return $this->seasonSeen;
    }

    /**
     * Set follow
     *
     * @param boolean $follow
     *
     * @return Collected
     */
    public function setFollow($follow)
    {
        $this->follow = $follow;

        return $this;
    }

    /**
     * Get follow
     *
     * @return bool
     */
    public function getFollow()
    {
        return $this->follow;
    }

    /**
     * Set alertFirstEpisode
     *
     * @param boolean $alertFirstEpisode
     *
     * @return Collected
     */
    public function setAlertFirstEpisode($alertFirstEpisode)
    {
        $this->alertFirstEpisode = $alertFirstEpisode;

        return $this;
    }

    /**
     * Get alertFirstEpisode
     *
     * @return bool
     */
    public function getAlertFirstEpisode()
    {
        return $this->alertFirstEpisode;
    }

    /**
     * Set alertLastEpisode
     *
     * @param boolean $alertLastEpisode
     *
     * @return Collected
     */
    public function setAlertLastEpisode($alertLastEpisode)
    {
        $this->alertLastEpisode = $alertLastEpisode;

        return $this;
    }

    /**
     * Get alertLastEpisode
     *
     * @return bool
     */
    public function getAlertLastEpisode()
    {
        return $this->alertLastEpisode;
    }

    /**
     * Set alertEachEpisode
     *
     * @param boolean $alertEachEpisode
     *
     * @return Collected
     */
    public function setAlertEachEpisode($alertEachEpisode)
    {
        $this->alertEachEpisode = $alertEachEpisode;

        return $this;
    }

    /**
     * Get alertEachEpisode
     *
     * @return bool
     */
    public function getAlertEachEpisode()
    {
        return $this->alertEachEpisode;
    }

    /**
     * Set user
     *
     * @param \Serie\UserBundle\Entity\User $user
     *
     * @return Collected
     */
    public function setUser(\Serie\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Serie\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set serie
     *
     * @param \Serie\DataBundle\Entity\Serie $serie
     *
     * @return Collected
     */
    public function setSerie(\Serie\DataBundle\Entity\Serie $serie = null)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return \Serie\DataBundle\Entity\Serie
     */
    public function getSerie()
    {
        return $this->serie;
    }
	
	public function getSeenDuration()
	{
		$duration = 0;
		for($i = 1; $i <= $this->seasonSeen; $i++)
		{
			$duration += ( $this->serie->getNbEpisodesInSeason($i) * $this->serie->getRuntime() );
		}
		return $duration;
		// return ($duration % 60)."h".(floor($duration / 60))."m";
	}
	
	public function getToSeeDuration()
	{
		$duration = 0;
		for($i = $this->seasonSeen + 1; $i <= $this->serie->getNbSeasons(); $i++)
		{
			$duration += ( $this->serie->getNbEpisodesInSeason($i) * $this->serie->getRuntime() );
		}
		return $duration;	
		// return ($duration % 60)."h".(floor($duration / 60))."m";		
	}

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Collected
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Collected
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set episodeSeen
     *
     * @param integer $episodeSeen
     *
     * @return Collected
     */
    public function setEpisodeSeen($episodeSeen)
    {
        $this->episodeSeen = $episodeSeen;

        return $this;
    }

    /**
     * Get episodeSeen
     *
     * @return integer
     */
    public function getEpisodeSeen()
    {
        return $this->episodeSeen;
    }

    /**
     * Update status (value : 'Not started', 'Continuing', 'Ended')
     *
     * 
     */

	 public function updateStatus()
	{
		if((!$this->follow) OR (($this->serie->getStatus() == "Ended") AND ($this->serie->getNbSeasons() == $this->seasonSeen) AND ($this->serie->getNbEpisodesInSeason($this->serie->getNbSeasons()) == $this->episodeSeen)))
			$this->status = "Ended";
		else if($this->seasonSeen > 0)
			$this->status = "Continuing";
		else 
			$this->status = "Not started";
	}
	
    /**
     * getNbAvailableEpisode
     *
     * @return integer
     */
    public function getNbAvailableEpisode()
    {

		$nbEpisode = 0;
		$today = new \DateTime('now');
		foreach($this->serie->getEpisodes()  as $key => $episode)
		{
			if($episode->getFirstAired() < $today AND $episode->getSeasonNumber() > 0) // If already aired and real season
			{
				if($episode->getSeasonNumber() == $this->seasonSeen AND $episode->getSeasonNumber() != $this->serie->getNbSeasons() AND $episode->getEpisodeNumber() > $this->episodeSeen) {
						$nbEpisode++;					
				} 
				elseif($episode->getSeasonNumber() > $this->seasonSeen AND $episode->getSeasonNumber() < $this->serie->getNbSeasons()) 
					$nbEpisode++;
				elseif($episode->getSeasonNumber() == $this->serie->getNbSeasons()) {
					if(!$this->serie->getNextEpisode()) {
						if($episode->getSeasonNumber() == $this->seasonSeen) {
							if($episode->getEpisodeNumber() > $this->episodeSeen)
								$nbEpisode++;
						} 
						else
							$nbEpisode++;
					}
					else {
						if($episode->getEpisodeNumber() < $this->serie->getNextEpisode()->getEpisodeNumber()) {
							if($episode->getSeasonNumber() == $this->seasonSeen) {
								if($episode->getEpisodeNumber() > $this->episodeSeen)
									$nbEpisode++;
							} 
							else
								$nbEpisode++;
						}
							
					}
				}				
			}
		}
		
		return $nbEpisode;
    }
	/**
     * detailsAvailableEpisode
     *
     * @return string
     */
    public function detailsAvailableEpisode()
    {
		
	}
}
