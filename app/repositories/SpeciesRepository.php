<?php

namespace App\Repositories;

use App\Entities\Species;
use App\Entities\User;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

class SpeciesRepository
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
     * SpeciesRepository constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Species::class);
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
     * @return Species|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return Species[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @return Species[]
     */
    public function findPairs()
    {
        return $this->repository->findPairs([], 'name');
    }

    /**
     * @param $values
     * @return Species
     */
    public function saveFormData($values)
    {
        $species = NULL;

        if (isset($values->id)) {
            $species = $this->find($values->id);
        }

        if ( ! $species) {
            $species = new Species();
        }

        $species->setName($values->name);
        $species->setOccurrence($values->occurrence);

        $this->entityManager->persist($species);
        $this->entityManager->flush();

        return $species;
    }
}
