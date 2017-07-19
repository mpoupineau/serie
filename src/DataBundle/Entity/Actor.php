<?php

namespace DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actor
 *
 * @ORM\Table(name="actor")
 * @ORM\Entity(repositoryClass="DataBundle\Repository\ActorRepository")
 */
class Actor
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
     * @ORM\Column(name="idDB", type="integer")
     */
    private $idDB;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=true)
     */
    private $role;

    /**
     * @var int
     *
     * @ORM\Column(name="sortOrder", type="integer", nullable=true)
     */
    private $sortOrder;

	/**
	* @ORM\ManyToOne(targetEntity="DataBundle\Entity\Serie", inversedBy="actors")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $serie;
	
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
	public function __construct()
	{
        $this->setCreatedAt(new \DateTime('now'));
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
     * @return Actor
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
     * @return Actor
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
     * Set role
     *
     * @param string $role
     *
     * @return Actor
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return Actor
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set serie
     *
     * @param Serie $serie
     *
     * @return Actor
     */
    public function setSerie(Serie $serie = null)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return Serie
     */
    public function getSerie()
    {
        return $this->serie;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Actor
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
     * @return Actor
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
    
    
    public function populate($data) {
        $this->setIdDB($data->id);
        $this->setName($data->name);
        $this->setRole($data->role);
        $this->setSortOrder($data->sortOrder);
    }
}
