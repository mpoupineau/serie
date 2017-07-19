<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Entrez votre prénom.", groups={"Registration", "Profile"})
	 * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Entrez votre nom.", groups={"Registration", "Profile"})
	 * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $lastName;


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
	 * @ORM\OneToMany(targetEntity="DataBundle\Entity\Collected", mappedBy="user")
	 */
	private $collecteds;
    
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
	
    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Add collected
     *
     * @param \DataBundle\Entity\Collected $collected
     *
     * @return User
     */
    public function addCollected(\DataBundle\Entity\Collected $collected)
    {
        $this->collecteds[] = $collected;
		$collected->setUser($this);
        return $this;
    }

    /**
     * Remove collected
     *
     * @param \DataBundle\Entity\Collected $collected
     */
    public function removeCollected(\DataBundle\Entity\Collected $collected)
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * @return User
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
    
	public function getSeenDuration()
	{
		$duration = 0;
		foreach($this->collecteds  as $key => $collected)
		{
			$duration += $collected->getSeenDuration();
		}
		return $duration;
	}
	
	public function getToSeeDuration()
	{
		$duration = 0;
		foreach($this->collecteds  as $key => $collected)
		{
			$duration += $collected->getToSeeDuration();
		}
		return $duration;	
	}
}
