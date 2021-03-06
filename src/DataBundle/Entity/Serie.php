<?php

namespace DataBundle\Entity;

use Symfony\Component\DomCrawler\Crawler;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Serie
 *
 * @ORM\Table(name="serie")
 * @ORM\Entity(repositoryClass="DataBundle\Repository\SerieRepository")
 */
class Serie
{
    const IMPORT_INIT = 0;
    const IMPORT_WAITING = 1;
    const IMPORT_COMPLETE = 2;
    
    const NB_CATEGORY = 18;
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
     * @ORM\Column(name="idDB", type="integer", unique=true)
     */
    private $idDB;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	
	/**
	 * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
	private $slug;

	/**
     * @var string
     *
     * @ORM\Column(name="actor", type="text", nullable=true)
     */
    private $actor;

    /**
     * @var string
     *
     * @ORM\Column(name="airs_dayOfWeek", type="string", length=255, nullable=true)
     */
    private $airsDayOfWeek;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="firstAired", type="date", nullable=true)
     */
    private $firstAired;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="text", nullable=true)
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=5, nullable=true)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="overview", type="text", nullable=true)
     */
    private $overview;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", nullable=true)
     */
    private $rating;
    
    /**
     * @var int
     *
     * @ORM\Column(name="ratingCount", type="integer", nullable=true)
     */
    private $ratingCount;

    /**
     * @var int
     *
     * @ORM\Column(name="runtime", type="integer", nullable=true)
     */
    private $runtime;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="banner", type="string", length=255, nullable=true)
     */
    private $banner;

    /**
     * @var string
     *
     * @ORM\Column(name="fanart", type="string", length=255, nullable=true)
     */
    private $fanart;

    /**
     * @var string
     *
     * @ORM\Column(name="poster", type="string", length=255, nullable=true)
     */
    private $poster;

    /**
     * @var int
     *
     * @ORM\Column(name="lastUpdated", type="bigint", nullable=true)
     */
    private $lastUpdated;
	
	/**
	 * @ORM\OneToMany(targetEntity="DataBundle\Entity\Episode", mappedBy="serie", cascade={"persist"})
	 */
	private $episodes;

	/**
	 * @ORM\OneToMany(targetEntity="DataBundle\Entity\Actor", mappedBy="serie", cascade={"persist"})
	 */
	private $actors;
	
	/**
	 * @ORM\OneToMany(targetEntity="DataBundle\Entity\Collected", mappedBy="serie")
	 */
	private $collecteds;
    
    /**
     * @var int
     *
     * @ORM\Column(name="importStatus", type="bigint", nullable=true)
     */
    private $importStatus;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;
	
	/*public function __construct()
	{
	}*/
	/*public function __construct($type, $crawler_serie)
	{
		if($type == "byCrawler") {
			$this->setIdDB($crawler_serie->filterXpath(TAG_SERIE.'id')->text());
			$this->setName($crawler_serie->filterXpath(TAG_SERIE.'SeriesName')->text());
			$this->setActor($crawler_serie->filterXpath(TAG_SERIE.'Actors')->text());
			$this->setAirsDayOfWeek($crawler_serie->filterXpath(TAG_SERIE.'Airs_DayOfWeek')->text());
			$this->setFirstAired(new \DateTime($crawler_serie->filterXpath(TAG_SERIE.'FirstAired')->text()));
			$this->setGenre($crawler_serie->filterXpath(TAG_SERIE.'Genre')->text());
			$this->setLanguage($crawler_serie->filterXpath(TAG_SERIE.'Language')->text());
			$this->setOverview($crawler_serie->filterXpath(TAG_SERIE.'Overview')->text());
			$this->setRating($crawler_serie->filterXpath(TAG_SERIE.'Rating')->text());
			$this->setRuntime($crawler_serie->filterXpath(TAG_SERIE.'Runtime')->text());
			$this->setStatus($crawler_serie->filterXpath(TAG_SERIE.'Status')->text());
			$this->setBanner($crawler_serie->filterXpath(TAG_SERIE.'banner')->text());
			$this->setFanart($crawler_serie->filterXpath(TAG_SERIE.'fanart')->text());
			$this->setPoster($crawler_serie->filterXpath(TAG_SERIE.'poster')->text());
			$this->setLastUpdated($crawler_serie->filterXpath(TAG_SERIE.'lastupdated')->text());
		}
		elseif ($type == "empty") {
			
		}
	}*/
    
    /*public function __construct()
	{
	}*/
	public function __construct()
	{
        $this->setCreatedAt(new \DateTime('now'));
    }
	
	public function updateData($crawler_serie)
	{
		$this->setActor($crawler_serie->filterXpath(TAG_SERIE.'Actors')->text());
		$this->setAirsDayOfWeek($crawler_serie->filterXpath(TAG_SERIE.'Airs_DayOfWeek')->text());
		$this->setFirstAired(new \DateTime($crawler_serie->filterXpath(TAG_SERIE.'FirstAired')->text()));
		$this->setGenre($crawler_serie->filterXpath(TAG_SERIE.'Genre')->text());
		$this->setLanguage($crawler_serie->filterXpath(TAG_SERIE.'Language')->text());
		$this->setOverview($crawler_serie->filterXpath(TAG_SERIE.'Overview')->text());
		$this->setRating($crawler_serie->filterXpath(TAG_SERIE.'Rating')->text());
		$this->setRuntime($crawler_serie->filterXpath(TAG_SERIE.'Runtime')->text());
		$this->setStatus($crawler_serie->filterXpath(TAG_SERIE.'Status')->text());
		$this->setBanner($crawler_serie->filterXpath(TAG_SERIE.'banner')->text());
		$this->setFanart($crawler_serie->filterXpath(TAG_SERIE.'fanart')->text());
		if((stripos($this->getPoster(), 'default/') !== false) AND ($crawler_serie->filterXpath(TAG_SERIE.'poster')->text() !== ''))
			$this->setPoster($crawler_serie->filterXpath(TAG_SERIE.'poster')->text());
		$this->setLastUpdated($crawler_serie->filterXpath(TAG_SERIE.'lastupdated')->text());
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
     * Set idDB
     *
     * @param integer $idDB
     *
     * @return Serie
     */
    public function setIdDB($idDB)
    {
        $this->idDB = $idDB;

        return $this;
    }

    /**
     * Get idDB
     *
     * @return int
     */
    public function getIdDB()
    {
        return $this->idDB;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Serie
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
     * Set actor
     *
     * @param string $actor
     *
     * @return Serie
     */
    public function setActor($actor)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get actor
     *
     * @return string
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Set airsDayOfWeek
     *
     * @param string $airsDayOfWeek
     *
     * @return Serie
     */
    public function setAirsDayOfWeek($airsDayOfWeek)
    {
        $this->airsDayOfWeek = $airsDayOfWeek;

        return $this;
    }

    /**
     * Get airsDayOfWeek
     *
     * @return string
     */
    public function getAirsDayOfWeek()
    {
        return $this->airsDayOfWeek;
    }

    /**
     * Set firstAired
     *
     * @param \DateTime $firstAired
     *
     * @return Serie
     */
    public function setFirstAired($firstAired)
    {
        $this->firstAired = $firstAired;

        return $this;
    }

    /**
     * Get firstAired
     *
     * @return \DateTime
     */
    public function getFirstAired()
    {
        return $this->firstAired;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Serie
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Serie
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set overview
     *
     * @param string $overview
     *
     * @return Serie
     */
    public function setOverview($overview)
    {
        $this->overview = $overview;

        return $this;
    }

    /**
     * Get overview
     *
     * @return string
     */
    public function getOverview()
    {
        return $this->overview;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Serie
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set runtime
     *
     * @param integer $runtime
     *
     * @return Serie
     */
    public function setRuntime($runtime)
    {
        $this->runtime = $runtime;

        return $this;
    }

    /**
     * Get runtime
     *
     * @return int
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Serie
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
     * Set banner
     *
     * @param string $banner
     *
     * @return Serie
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Get banner
     *
     * @return string
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Set fanart
     *
     * @param string $fanart
     *
     * @return Serie
     */
    public function setFanart($fanart)
    {
        $this->fanart = $fanart;

        return $this;
    }

    /**
     * Get fanart
     *
     * @return string
     */
    public function getFanart()
    {
        return $this->fanart;
    }

    /**
     * Set poster
     *
     * @param string $poster
     *
     * @return Serie
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get poster
     *
     * @return string
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Set lastUpdated
     *
     * @param integer $lastUpdated
     *
     * @return Serie
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return int
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
	}

    /**
     * Add episode
     *
     * @param Episode $episode
     *
     * @return Serie
     */
    public function addEpisode(Episode $episode)
    {
        $this->episodes[] = $episode;
		$episode->setSerie($this);
        return $this;
    }
    
    public function setEpisodes($episodes)
    {
        foreach($episodes as $episode)
        {
            $this->episodes[] = $episode;
            $episode->setSerie($this);
        }
    }

    /**
     * Remove episode
     *
     * @param \DataBundle\Entity\Episode $episode
     */
    public function removeEpisode(Episode $episode)
    {
        $this->episodes->removeElement($episode);
    }
    
    public function removeEpisodes()
    {
        $episodes = $this->episodes;
        foreach($episodes as $episode)
        {
            $this->episodes->removeElement($episode);
        }
    }

    /**
     * Get episodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpisodes()
    {
        return $this->episodes;
    }
    /**
     * Get episode 
	 * 
     * @param Integer $nbSeason
     * @param Integer $nbEpisode
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpisode($nbSeason, $nbEpisode)
    {
		foreach($this->episodes  as $key => $episode)
		{
			if($episode->getSeasonNumber() == $nbSeason AND $episode->getEpisodeNumber() == $nbEpisode)
			{
				return $episode;
			}
		}
        return new Episode();
    }
	
    /**
     * Add actor
     *
     * @param Actor $actor
     *
     * @return Serie
     */
    public function addActor(Actor $actor)
    {
        $this->actors[] = $actor;
		$actor->setSerie($this);
        return $this;
    }

    /**
     * Remove actor
     *
     * @param \DataBundle\Entity\Actor $actor
     */
    public function removeActor(Actor $actor)
    {
        $this->actors->removeElement($actor);
    }	
	
    /**
     * Get actors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * Add collected
     *
     * @param \DataBundle\Entity\Collected $collected
     *
     * @return Serie
     */
    public function addCollected(Collected $collected)
    {
        $this->collecteds[] = $collected;
		$collected->setSerie($this);
        return $this;
    }

    /**
     * Remove collected
     *
     * @param Collected $collected
     */
    public function removeCollected(Collected $collected)
    {
        $this->collecteds->removeElement($collected);
    }

    /**
     * Get collecteds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollecteds()
    {
        return $this->collecteds;
    }
	
    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Serie
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
        
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Serie
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
        /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Serie
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

	public function populate($infoSerie) {
        $this->setIdDB($infoSerie->id);
        $this->setName($infoSerie->seriesName);
        $this->setAirsDayOfWeek($infoSerie->airsDayOfWeek);
        $this->setFirstAired(new \DateTime($infoSerie->firstAired));
        $this->setGenre(implode('|', $infoSerie->genre));
        $this->setOverview($infoSerie->overview);
        $this->setRating($infoSerie->rating);
        $this->setRuntime($infoSerie->runtime);
        $this->setStatus($infoSerie->status);
        $this->setBanner($infoSerie->banner);
        $this->setLastUpdated($infoSerie->lastUpdated);
    }
    
    public function minPopulate($infoSearchSerie) {
        $this->setIdDB($infoSearchSerie->id);
        $this->setName($infoSearchSerie->seriesName);
        $this->setImportStatus($this::IMPORT_INIT);
    }
	/**
     * Get number of episodes from a season
     *
     * @return Integer
     */
    public function getNbEpisodesInSeason($season)
    {
		$today = new \DateTime('now');
		$maxNbEpisode = 0;
		foreach($this->episodes  as $key => $episode)
		{
			if($episode->getSeasonNumber() == $season AND $episode->getFirstAired() < $today)
			{
				if($episode->getEpisodeNumber() > $maxNbEpisode)
				{
					$maxNbEpisode = $episode->getEpisodeNumber();
				}
			}
		}
        return $maxNbEpisode;
    }
	
	/**
     * Get number of season from a serie
     *
     * @return Integer
     */
    public function getNbSeasons()
    {
		$today = new \DateTime('now');
		$maxNbSeason = 0;
		foreach($this->episodes  as $key => $episode)
		{
			if($episode->getSeasonNumber() > $maxNbSeason AND $episode->getFirstAired() < $today)
			{
				$maxNbSeason = $episode->getSeasonNumber();
			}
		}
        return $maxNbSeason;
    }
	/**
     * Get number of season from a serie
     *
     * @return Integer
     */
    public function getNbEpisodes()
    {
		$nbEpisodes = 0;
		for($i = 1 ; $i <= $this->getNbSeasons() ; $i++)
		{
			$nbEpisodes += $this->getNbEpisodesInSeason($i);
		}
        return $nbEpisodes;
    }
	/**
     * Get duration (minutes)
     *
     * @return Integer
     */
    public function getDurationMin()
    {
		if($this->runtime == 0)
			return 0;
		else {
			return ($this->runtime * $this->getNbEpisodes()) % 60;
		}
    }
	/**
     * Get duration (hour)
     *
     * @return Integer
     */
    public function getDurationHour()
    {
		if($this->runtime == 0)
			return 0;
		else {
			return floor(($this->runtime * $this->getNbEpisodes()) / 60);
		}
    }
	/**
     * Get next episode
     *
     * @return episode
     */
    public function getNextEpisode()
    {
		$today = new \DateTime('now');
		$closestDayEpisode = new \DateTime('+ 2 years');
		$episodeToReturn = false;
		for($i = 0; $i < count($this->episodes); $i++)
		{
			if(($today < $this->episodes[$i]->getFirstAired()) AND ($this->episodes[$i]->getFirstAired() < $closestDayEpisode) AND ($this->episodes[$i]->getSeasonNumber() > 0))
			{
				$closestDayEpisode = $this->episodes[$i]->getFirstAired();
				$episodeToReturn = $i;
			}
		}
		if($episodeToReturn === false)
			return $episodeToReturn;
		else
			return $this->episodes[$episodeToReturn];
    }
	/**
     * Get next saison
     *
     * @return episode
     */
    public function getNextSaison()
    {
		$today = new \DateTime('now');
		$closestDayEpisode = new \DateTime('+ 2 years');
		$episodeToReturn = false;
		for($i = 0; $i < count($this->episodes); $i++)
		{
			if(($today < $this->episodes[$i]->getFirstAired()) AND ($this->episodes[$i]->getFirstAired() < $closestDayEpisode) AND ($this->episodes[$i]->getSeasonNumber() > 0) AND ($this->episodes[$i]->getEpisodeNumber() == 1))
			{
				$closestDayEpisode = $this->episodes[$i]->getFirstAired();
				$episodeToReturn = $i;
			}
		}
		if($episodeToReturn === false)
			return $episodeToReturn;
		else
			return $this->episodes[$episodeToReturn];
    }
	
	/**
     * Is in collection
     *
     * @return collected
     */
    public function isInCollection($user_id)
    {
		foreach($this->collecteds  as $key => $collected)
		{
			if($collected->getUser()->getId() == $user_id)
			{
				return $collected;
			}
		}
		return false;
    }


    /**
     * Set importStatus
     *
     * @param integer $importStatus
     *
     * @return Serie
     */
    public function setImportStatus($importStatus)
    {
        $this->importStatus = $importStatus;

        return $this;
    }

    /**
     * Get importStatus
     *
     * @return integer
     */
    public function getImportStatus()
    {
        return $this->importStatus;
    }

    /**
     * Set ratingCount
     *
     * @param integer $ratingCount
     *
     * @return Serie
     */
    public function setRatingCount($ratingCount)
    {
        $this->ratingCount = $ratingCount;

        return $this;
    }

    /**
     * Get ratingCount
     *
     * @return integer
     */
    public function getRatingCount()
    {
        return $this->ratingCount;
    }
}
