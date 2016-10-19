<?php

namespace App\Repositories;

use App\Entities\CleaningType;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

class CleaningTypeRepository
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
     * CleaningRepository constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(CleaningType::class);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param $id
     * @return CleaningType|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return CleaningType[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @return CleaningType[]
     */
    public function findPairs()
    {
        return $this->repository->findPairs([], 'name');
    }

    public function saveFormData($values)
    {
        $cleaningType = NULL;

        if (isset($values->id)) {
            $cleaningType = $this->find($values->id);
        }

        if ( ! $cleaningType) {
            $cleaningType = new CleaningType();
        }

        $cleaningType->setName($values->name);
        $cleaningType->setTools($values->tools);

        $this->entityManager->persist($cleaningType);
        $this->entityManager->flush();

        return $cleaningType;
    }
}
