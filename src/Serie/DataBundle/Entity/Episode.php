<?php

namespace Serie\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Episode
 *
 * @ORM\Table(name="episode")
 * @ORM\Entity(repositoryClass="Serie\DataBundle\Repository\EpisodeRepository")
 */
class Episode
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
     * @ORM\Column(name="idDB", type="integer", unique=true)
     */
    private $idDB;
	
    /**
     * @var int
     *
     * @ORM\Column(name="seasonNumber", type="integer")
     */
    private $seasonNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="episodeNumber", type="integer")
     */
    private $episodeNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="episodeName", type="string", length=255, nullable=true)
     */
    private $episodeName;

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
     * @var string
     *
     * @ORM\Column(name="episodeImage", type="string", length=255, nullable=true)
     */
    private $episodeImage;

    /**
     * @var string
     *
     * @ORM\Column(name="director", type="string", length=255, nullable=true)
     */
    private $director;

    /**
     * @var string
     *
     * @ORM\Column(name="writer", type="string", length=255, nullable=true)
     */
    private $writer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="firstAired", type="date", nullable=true)
     */
    private $firstAired;

    /**
     * @var int
     *
     * @ORM\Column(name="lastUpdated", type="bigint", nullable=true)
     */
    private $lastUpdated;

	/**
	* @ORM\ManyToOne(targetEntity="Serie\DataBundle\Entity\Serie", inversedBy="episodes")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $serie;

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
     * Set seasonNumber
     *
     * @param integer $seasonNumber
     *
     * @return Episode
     */
    public function setSeasonNumber($seasonNumber)
    {
        $this->seasonNumber = $seasonNumber;

        return $this;
    }

    /**
     * Get seasonNumber
     *
     * @return int
     */
    public function getSeasonNumber()
    {
        return $this->seasonNumber;
    }

    /**
     * Set episodeNumber
     *
     * @param integer $episodeNumber
     *
     * @return Episode
     */
    public function setEpisodeNumber($episodeNumber)
    {
        $this->episodeNumber = $episodeNumber;

        return $this;
    }

    /**
     * Get episodeNumber
     *
     * @return int
     */
    public function getEpisodeNumber()
    {
        return $this->episodeNumber;
    }

    /**
     * Set episodeName
     *
     * @param string $episodeName
     *
     * @return Episode
     */
    public function setEpisodeName($episodeName)
    {
        $this->episodeName = $episodeName;

        return $this;
    }

    /**
     * Get episodeName
     *
     * @return string
     */
    public function getEpisodeName()
    {
        return $this->episodeName;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Episode
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
     * @return Episode
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
     * @return Episode
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
     * Set episodeImage
     *
     * @param string $episodeImage
     *
     * @return Episode
     */
    public function setEpisodeImage($episodeImage)
    {
        $this->episodeImage = $episodeImage;

        return $this;
    }

    /**
     * Get episodeImage
     *
     * @return string
     */
    public function getEpisodeImage()
    {
        return $this->episodeImage;
    }

    /**
     * Set director
     *
     * @param string $director
     *
     * @return Episode
     */
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return string
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set writer
     *
     * @param string $writer
     *
     * @return Episode
     */
    public function setWriter($writer)
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * Get writer
     *
     * @return string
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * Set firstAired
     *
     * @param \DateTime $firstAired
     *
     * @return Episode
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
     * Set lastUpdated
     *
     * @param integer $lastUpdated
     *
     * @return Episode
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
     * Set serie
     *
     * @param \Serie\DataBundle\Entity\Serie $serie
     *
     * @return Episode
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

    /**
     * Set idDB
     *
     * @param integer $idDB
     *
     * @return Episode
     */
    public function setIdDB($idDB)
    {
        $this->idDB = $idDB;

        return $this;
    }

    /**
     * Get idDB
     *
     * @return integer
     */
    public function getIdDB()
    {
        return $this->idDB;
    }
    
    public function populate($data) {
        $this->setSeasonNumber($data->airedSeason);
        $this->setEpisodeNumber($data->airedEpisodeNumber);
        $this->setEpisodeName($data->episodeName);
        $this->setOverview($data->overview);
        $this->setIdDB($data->id);
    }
}
