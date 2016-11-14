<?php
namespace Serie\DataBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BaseService
{

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var string
     */
    private $modelName;

    /**
     *
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param null                   $modelName
     * @param EntityRepository       $repository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        $modelName = null,
        EntityRepository $repository = null
    ) {
        $this->setEntityManager($entityManager);
        $this->modelName = $modelName;

        if ($this->modelName && ! $repository) {
            $this->repository = $entityManager->getRepository($this->modelName);
        }

        if ($repository) {
            $this->repository = $repository;
        }
    }

    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \LibraryBundle\Service\BaseServiceInterface::find()
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return $this->getRepository()->find($id, $lockMode, $lockVersion);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \LibraryBundle\Service\BaseServiceInterface::findOneBy()
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->getRepository()->findOneBy($criteria, $orderBy);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \LibraryBundle\Service\BaseServiceInterface::findAll()
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * (non-PHPdoc)
     *
     * @see \LibraryBundle\Service\BaseServiceInterface::findBy()
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \LibraryBundle\Service\BaseServiceInterface::save()
     */
    public function save($model)
    {
        $this->getEntityManager()->persist($model);
        $this->update($model);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \LibraryBundle\Service\BaseServiceInterface::update()
     */
    public function update($object)
    {
        $this->getEntityManager()->flush($object);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return null|string
     */
    protected function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->repository;
    }

    /**
     * Refresh le model
     * @param $object
     */
    public function refresh($object)
    {
        $this->getEntityManager()->refresh($object);
    }

    /**
     * Remove an object
     * @param $object
     */
    public function remove($object)
    {
        $this->getEntityManager()->remove($object);
        $this->getEntityManager()->flush();
    }

    /**
     * (non-PHPdoc)
     *
     * @see \LibraryBundle\Service\BaseServiceInterface::getReference()
     */
    public function getReference($id)
    {
        return $this->entityManager->getReference($this->modelName, $id);
    }
}

