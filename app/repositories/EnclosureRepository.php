<?php

namespace App\Repositories;

use App\Entities\Enclosure;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

class EnclosureRepository
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
     * @var EnclosureTypeRepository
     */
    private $enclosureTypeRepository;

    /**
     * EnclosureRepository constructor.
     *
     * @param EntityManager $entityManager
     * @param EnclosureTypeRepository $enclosureTypeRepository
     */
    public function __construct(EntityManager $entityManager, EnclosureTypeRepository $enclosureTypeRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Enclosure::class);
        $this->enclosureTypeRepository = $enclosureTypeRepository;
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
     * @return Enclosure|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return Enclosure[]
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
            $enclosureType = new Enclosure();
        }

        $enclosureType->setLabel($values->label);
        $enclosureType->setCapacity($values->capacity);
        $enclosureType->setSize($values->size);
        $enclosureType->setEnclosureType($this->enclosureTypeRepository->find($values->enclosureType));

        $this->entityManager->persist($enclosureType);
        $this->entityManager->flush();

        return $enclosureType;
    }
}
