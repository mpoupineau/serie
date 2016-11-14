<?php

namespace Serie\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actor
 *
 * @ORM\Table(name="actor")
 * @ORM\Entity(repositoryClass="Serie\DataBundle\Repository\ActorRepository")
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
	* @ORM\ManyToOne(targetEntity="Serie\DataBundle\Entity\Serie", inversedBy="actors")
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
     * @param \Serie\DataBundle\Entity\Serie $serie
     *
     * @return Actor
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
    
    public function populate($data) {
        $this->setIdDB($data->id);
        $this->setName($data->name);
        $this->setRole($data->role);
        $this->setSortOrder($data->sortOrder);
    }
}
