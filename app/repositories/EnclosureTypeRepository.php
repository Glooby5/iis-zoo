<?php

namespace App\Repositories;

use App\Entities\EnclosureType;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

class EnclosureTypeRepository
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
     * EnclosureRepository constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(EnclosureType::class);
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
     * @return EnclosureType|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return EnclosureType[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function saveFormData($values)
    {
        $enclosureType = NULL;

        if (isset($values->id)) {
            $enclosureType = $this->find($values->id);
        }

        if ( ! $enclosureType) {
            $enclosureType = new EnclosureType();
        }

        $enclosureType->setName($values->name);

        $this->entityManager->persist($enclosureType);
        $this->entityManager->flush();

        return $enclosureType;
    }
}
