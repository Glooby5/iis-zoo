<?php

namespace App\Repositories;

use App\Entities\User;
use Doctrine\ORM\Mapping;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

class UserRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * UserRepository constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    /**
     * @param $id
     * @return User|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $options
     * @return User|null
     */
    public function findOneBy(array $options)
    {
        return $this->repository->findOneBy($options);
    }

    /**
     * @param array $options
     * @return User[]|null
     */
    public function findBy(array $options)
    {
        return $this->repository->findBy($options);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }



}
